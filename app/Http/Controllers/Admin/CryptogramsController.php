<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cryptogram\BulkDestroyCryptogram;
use App\Http\Requests\Admin\Cryptogram\DestroyCryptogram;
use App\Http\Requests\Admin\Cryptogram\IndexCryptogram;
use App\Http\Requests\Admin\Cryptogram\StoreCryptogram;
use App\Http\Requests\Admin\Cryptogram\UpdateCryptogram;
use App\Http\Requests\Admin\General\UpdateState;
use App\Mail\UpdateCryptogramStateMail;
use App\Models\Category;
use App\Models\CipherKey;
use App\Models\Cryptogram;
use App\Models\Data;
use App\Models\Datagroup;
use App\Models\Language;
use App\Models\Location;
use App\Models\Person;
use App\Models\Solution;
use App\Models\State;
use App\Models\Tag;
use App\Traits\Cryptogram\CryptogramSyncable;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CryptogramsController extends Controller
{
    use CryptogramSyncable;

    /**
     * Display a listing of the resource.
     *
     * @param IndexCryptogram $request
     * @return array|Factory|View
     */
    public function index(IndexCryptogram $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Cryptogram::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['availability', 'category_id', 'id', 'date', 'date_around', 'image_url', 'language_id', 'location_id', 'name', 'recipient_id', 'sender_id', 'solution_id', 'state', 'created_by'],

            // set columns to searchIn
            ['availability', 'description', 'id', 'image_url', 'name'],

            function (Builder $query) {
                $query->with(['language', 'submitter']);
            }
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.cryptogram.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.cryptogram.create');

        $locations = Location::all();
        $languages = Language::all();
        $persons = Person::all();
        $tags = Tag::where('type', Tag::CRYPTOGRAM)
            ->orWhereNull('type')
            ->get();
        $solutions = Solution::all();
        $categories = Category::with('children')->whereNull('parent_id')->orderBy('name', 'asc')->get();
        $groups = Datagroup::all();
        $continents = collect(Location::CONTINENTS)->toJSON();
        $states = collect(CipherKey::STATUSES)->toJSON();

        return view('admin.cryptogram.create', compact(
            'locations',
            'languages',
            'persons',
            'tags',
            'solutions',
            'categories',
            'groups',
            'states',
            'continents'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCryptogram $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreCryptogram $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();


        // Store the Cryptogram
        $cryptogram = Cryptogram::create($sanitized);

        //Sync thumbnail
        if (isset($sanitized['thumbnail']) && $sanitized['thumbnail']) {
            $cryptogram
                ->addMediaFromBase64($sanitized['thumbnail']) //starting method
                ->toMediaCollection('picture'); //finishing method
        }

        //Sync datagroups
        $this->syncDatagroups($cryptogram, $sanitized);

        //Sync tags
        $this->syncTags($cryptogram, $sanitized);

        //Sync cipher keys
        $this->syncCipherKeys($cryptogram, $sanitized);

        alert()->success('Success', 'Sucessfully added cryptogram.');

        if ($request->ajax()) {
            return ['redirect' => url('admin/cryptograms'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/cryptograms');
    }

    /**
     * Display the specified resource.
     *
     * @param Cryptogram $cryptogram
     * @throws AuthorizationException
     * @return void
     */
    public function show(Cryptogram $cryptogram)
    {
        $this->authorize('admin.cryptogram.show', $cryptogram);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Cryptogram $Cryptogram
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Cryptogram $cryptogram)
    {
        $this->authorize('admin.cryptogram.edit', $cryptogram);

        $locations = Location::all();
        $languages = Language::all();
        $persons = Person::all();
        $tags = Tag::where('type', Tag::CRYPTOGRAM)
            ->orWhereNull('type')
            ->get();
        $solutions = Solution::all();
        $categories = Category::with('children')->whereNull('parent_id')->orderBy('name', 'asc')->get();
        $states = collect(CipherKey::STATUSES)->toJSON();
        $continents = collect(Location::CONTINENTS)->toJSON();

        $cryptogram->load(['category', 'recipient', 'sender', 'tags', 'solution', 'language', 'groups', 'groups.data', 'cipherKeys']);

        //Category transformation
        if ($cryptogram->category->parent) {
            $categoryParent = $cryptogram->category->parent()->with('children')->first();
            $cryptogram->subcategory = $cryptogram->category;
            $cryptogram->category = $categoryParent;
        } else {
            $cryptogram->category = $cryptogram->category;
            $cryptogram->subcategory = "";
        }

        $cryptogram->unsetRelation('category');


        return view('admin.cryptogram.edit', [
            'cryptogram' => $cryptogram,
            'locations' => $locations,
            'languages' => $languages,
            'persons' => $persons,
            'tags' => $tags,
            'solutions' => $solutions,
            'categories' => $categories,
            'states' => $states,
            'continents' => $continents
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCryptogram $request
     * @param Cryptogram $Cryptogram
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateCryptogram $request, Cryptogram $cryptogram)
    {
        // Sanitize input

        $sanitized = $request->getSanitized();

        //Send mail to submitter if state changed
        if ($sanitized['state'] !== $cryptogram->state['id']) {
            Mail::to($cryptogram->submitter->email)->send(new UpdateCryptogramStateMail($cryptogram));
        }

        if (isset($sanitized['note_new'])) {
            $sanitized['note'] = $cryptogram->note . "\n" . $sanitized['note_new'];
        }

        // Update changed values Cryptogram
        $cryptogram->update($sanitized);


        //Sync thumbnail
        if (isset($sanitized['thumbnail']) && $sanitized['thumbnail']) {
            $cryptogram
                ->addMediaFromBase64($sanitized['thumbnail']) //starting method
                ->toMediaCollection('picture'); //finishing method
        }

        //Sync datagroups
        $this->syncDatagroups($cryptogram, $sanitized);

        //Sync tags
        $this->syncTags($cryptogram, $sanitized);

        //Sync cipher keys
        $this->syncCipherKeys($cryptogram, $sanitized);


        alert()->success('Success', 'Sucessfully updated cryptogram.');

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/cryptograms'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/cryptograms');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyCryptogram $request
     * @param Cryptogram $Cryptogram
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyCryptogram $request, Cryptogram $cryptogram)
    {
        $cryptogram->delete();

        alert()->success('Success', 'Sucessfully deleted cryptogram.');

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyCryptogram $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyCryptogram $request): Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Cryptogram::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        alert()->success('Success', 'Sucessfully deleted selected cryptograms.');

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }


    /**
     * Update cryptograms state
     *
     * @param UpdateStateCipherKey $request
     * @param Cryptogram $cryptogram
     * @return void
     */
    public function changeState(UpdateState $request, Cryptogram $cryptogram)
    {
        $sanitized = $request->getSanitized();

        $state = State::create([
            'name' => $cryptogram->name,
            'state' => $sanitized['state'],
            'note' => $sanitized['note'],
            'created_by' => auth()->user()->id
        ]);

        $cryptogram->update(['state_id' => $state->id]);

        Mail::to($cryptogram->submitter->email)->send(new UpdateCryptogramStateMail($cryptogram));

        alert()->success('Success', 'Sucessfully changed state of cryptogram.');

        return response()->json('Successfully status changed.', 200);
    }

    /**
     * Search cryptograms
     *
     * @param Request $request
     * @return Response
     */
    public function search(Request $request)
    {
        $results = Cryptogram::latest('id')->get();
        if ($request->search) {
            $results  = Cryptogram::where('name', 'LIKE', "%{$request->search}%")->orderBy('id', 'desc')->get();
        }
        return response()->json($results);
    }

    /**
     * Pair keys and cryptograms
     *
     * @param Request $request
     * @return void
     */
    public function bulkPairKeysAndCryptograms()
    {
        return view('admin.cryptogram.pairing.create');
    }


    /**
     * Save pair keys and cryptograms
     *
     * @param Request $request
     * @return void
     */
    public function saveBulkPairKeysAndCryptograms(Request $request)
    {
        $keys = collect($request->keys)->pluck('id')->toArray();
        $cryptograms = collect($request->cryptograms)->pluck('id')->toArray();

        $cryptograms = Cryptogram::whereIn('id', $cryptograms)->get();

        foreach ($cryptograms as $cryptogram) {
            $cryptogram->cipherKeys()->sync($keys);
        }

        alert()->success('Success', 'Sucessfully paired keys and cryptograms.');

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/cryptograms'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/cryptograms');
    }
}
