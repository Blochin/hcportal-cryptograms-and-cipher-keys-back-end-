<?php

namespace App\Http\Controllers\Admin;

use App\Http\Actions\Migrations\CipherKeysMigration;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CipherKey\BulkDestroyCipherKey;
use App\Http\Requests\Admin\CipherKey\DestroyCipherKey;
use App\Http\Requests\Admin\CipherKey\IndexCipherKey;
use App\Http\Requests\Admin\CipherKey\StoreCipherKey;
use App\Http\Requests\Admin\CipherKey\UpdateCipherKey;
use App\Http\Requests\Admin\CipherKey\UpdateStateCipherKey;
use App\Http\Requests\Admin\General\UpdateState;
use App\Mail\UpdateCipherKeyStateMail;
use App\Models\Archive;
use App\Models\Category;
use App\Models\CipherKey;
use App\Models\CipherType;
use App\Models\Folder;
use App\Models\Fond;
use App\Models\KeyType;
use App\Models\Language;
use App\Models\Location;
use App\Models\Person;
use App\Models\State;
use App\Models\Tag;
use App\Traits\CipherKey\CipherKeySyncable;
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

class CipherKeysController extends Controller
{
    use CipherKeySyncable;

    /**
     * Display a listing of the resource.
     *
     * @param IndexCipherKey $request
     * @return array|Factory|View
     */
    public function index(IndexCipherKey $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(CipherKey::class)->processRequestAndGet(
        // pass the request with params
            $request,

            // set columns to query
            ['id', 'category_id', 'complete_structure', 'name', 'created_by', 'key_type', 'used_from', 'used_to', 'used_around', 'folder_id', 'location_id', 'language_id', 'group_id', 'state'],

            // set columns to searchIn
            ['id', 'description', 'name', 'complete_structure', 'used_chars', 'category_id', 'key_type', 'used_around'],

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

        return view('admin.cipher-key.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('admin.cipher-key.create');

        $keyTypes = KeyType::all();
        $categories = Category::with('children')->whereNull('parent_id')->orderBy('name', 'asc')->get();
        $locations = Location::all();
        $languages = Language::all();
        $groups = CipherKey::all();
        $archives = Archive::all();
        $folders = Folder::all();
        $fonds = Fond::all();
        $users = Person::all();
        $tags = Tag::where('type', Tag::CIPHER_KEY)
            ->orWhereNull('type')
            ->get();
        $continents = collect(Location::CONTINENTS)->toJSON();
        $states = collect(CipherKey::STATUSES)->toJSON();


        return view('admin.cipher-key.create', compact(
            'keyTypes',
            'categories',
            'locations',
            'languages',
            'groups',
            'archives',
            'folders',
            'fonds',
            'users',
            'tags',
            'continents',
            'states'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCipherKey $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreCipherKey $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the CipherKey
        $cipherKey = CipherKey::create($sanitized);

        //Store CipherKey Images
        $this->syncCipherKeyImages($cipherKey, $sanitized);

        //Store CipherKey Users
        $this->syncCipherKeyUsers($cipherKey, $sanitized);

        //Store archives,fonds,folders
        $this->syncArchive($cipherKey, $sanitized);

        //Sync tags
        $this->syncTags($cipherKey, $sanitized);

        alert()->success('Success', 'Sucessfully added cipher key.');

        if ($request->ajax()) {
            return ['redirect' => url('admin/cipher-keys'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }


        return redirect('admin/cipher-keys');
    }

    /**
     * Display the specified resource.
     *
     * @param CipherKey $cipherKey
     * @return void
     * @throws AuthorizationException
     */
    public function show(CipherKey $cipherKey)
    {
        $this->authorize('admin.cipher-key.show', $cipherKey);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CipherKey $cipherKey
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function edit(CipherKey $cipherKey)
    {
        $this->authorize('admin.cipher-key.edit', $cipherKey);


        $keyTypes = KeyType::all();
        $categories = Category::with('children')->whereNull('parent_id')->orderBy('name', 'asc')->get();
        $locations = Location::all();
        $languages = Language::all();
        $groups = CipherKey::all();
        $archives = Archive::all();
        $folders = Folder::all();
        $fonds = Fond::all();
        $users = Person::all();
        $tags = Tag::where('type', Tag::CIPHER_KEY)
            ->orWhereNull('type')
            ->get();
        $states = collect(CipherKey::STATUSES)->toJSON();

        $continents = collect(Location::CONTINENTS)->toJSON();


        //Load relationships
        $cipherKey->load([
            'images',
            'users',
            'group',
            'folder',
            'language',
            'location',
            'tags',
            'folder.fond',
            'submitter',
            'category',
            'keyType',
            'cryptograms',
        ]);

        //Category transformation
        if ($cipherKey->category->parent) {
            $categoryParent = $cipherKey->category->parent()->with('children')->first();
            $cipherKey->subcategory = $cipherKey->category;
            $cipherKey->category = $categoryParent;
        } else {
            $cipherKey->category = $cipherKey->category;
            $cipherKey->subcategory = "";
        }

        $cipherKey->unsetRelation('category');

        return view('admin.cipher-key.edit', compact(
            'keyTypes',
            'locations',
            'languages',
            'groups',
            'archives',
            'folders',
            'fonds',
            'users',
            'tags',
            'cipherKey',
            'states',
            'continents',
            'categories'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCipherKey $request
     * @param CipherKey $cipherKey
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateCipherKey $request, CipherKey $cipherKey)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        //Send mail to submitter if state changed
        if ($sanitized['state'] !== $cipherKey->state['id']) {
            Mail::to($cipherKey->submitter->email)->send(new UpdateCipherKeyStateMail($cipherKey));
        }

        if (isset($sanitized['note_new'])) {
            $sanitized['note'] = $cipherKey->note . "\n" . $sanitized['note_new'];
        }

        // Update changed values CipherKey
        $cipherKey->update($sanitized);

        //Store CipherKey Users
        $this->syncCipherKeyUsers($cipherKey, $sanitized, 'update');

        //Store archives,fonds,folders
        $this->syncArchive($cipherKey, $sanitized);

        //Sync tags
        $this->syncTags($cipherKey, $sanitized);

        //Sync cryptograms
        $this->syncCryptograms($cipherKey, $sanitized);


        alert()->success('Success', 'Sucessfully updated cipher key.');

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/cipher-keys'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }


        return redirect('admin/cipher-keys');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyCipherKey $request
     * @param CipherKey $cipherKey
     * @return ResponseFactory|RedirectResponse|Response
     * @throws Exception
     */
    public function destroy(DestroyCipherKey $request, CipherKey $cipherKey)
    {
        $cipherKey->delete();

        alert()->success('Success', 'Sucessfully deleted cipher key.');

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }


        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyCipherKey $request
     * @return Response|bool
     * @throws Exception
     */
    public function bulkDestroy(BulkDestroyCipherKey $request): Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    CipherKey::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        alert()->success('Success', 'Sucessfully deleted selected cipher keys.');

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }

    public function bulkUpload()
    {
        return view('admin.cipher-key.bulk-upload');
    }

    public function processBulkUpload(Request $request)
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $cipherKeysMigration = new CipherKeysMigration($from, $to);
        $cipherKeysMigration->handle();
        return redirect()->back()->with('success', 'Jobs mapped successfully.');
    }


    /**
     * Update cipher key's state
     *
     * @param UpdateStateCipherKey $request
     * @param CipherKey $cipherKey
     * @return void
     */
    public function changeState(UpdateState $request, CipherKey $cipherKey)
    {
        $sanitized = $request->getSanitized();


        $cipherKey->update(['state' => $sanitized['state']]);

        Mail::to($cipherKey->submitter->email)->send(new UpdateCipherKeyStateMail($cipherKey));

        return response()->json('Successfully status changed.', 200);
    }

    /**
     * Search cipher keys
     *
     * @param Request $request
     * @return Response
     */
    public function search(Request $request)
    {
        $results = CipherKey::latest('id')->get();
        if ($request->search) {
            $results = CipherKey::where('name', 'LIKE', "%{$request->search}%")->orWhere('complete_structure', 'LIKE', "%{$request->search}%")->orderBy('id', 'desc')->get();
        }
        return response()->json($results);
    }
}
