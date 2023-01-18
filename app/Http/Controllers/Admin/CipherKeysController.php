<?php

namespace App\Http\Controllers\Admin;

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
            ['id', 'cipher_type', 'complete_structure', 'signature', 'created_by', 'key_type', 'used_from', 'used_to', 'used_around', 'folder_id', 'location_id', 'language_id', 'group_id', 'state_id'],

            // set columns to searchIn
            ['id', 'description', 'signature', 'complete_structure', 'used_chars', 'cipher_type', 'key_type', 'used_around'],

            function (Builder $query) {
                $query->with(['state', 'language', 'submitter']);
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
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.cipher-key.create');

        $keyTypes = KeyType::all();
        $cipherTypes = CipherType::all();
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


        return view('admin.cipher-key.create', compact(
            'keyTypes',
            'cipherTypes',
            'locations',
            'languages',
            'groups',
            'archives',
            'folders',
            'fonds',
            'users',
            'tags',
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

        if ($request->ajax()) {
            return ['redirect' => url('admin/cipher-keys'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/cipher-keys');
    }

    /**
     * Display the specified resource.
     *
     * @param CipherKey $cipherKey
     * @throws AuthorizationException
     * @return void
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
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(CipherKey $cipherKey)
    {
        $this->authorize('admin.cipher-key.edit', $cipherKey);


        $keyTypes = collect(CipherKey::KEY_TYPES)->toJSON();
        $cipherTypes = collect(CipherKey::CIPHER_TYPES)->toJSON();
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
        $states = collect(State::STATUSES)->toJSON();

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
            'cipherType',
            'keyType',
            'cryptograms'
        ]);


        return view('admin.cipher-key.edit', compact(
            'keyTypes',
            'cipherTypes',
            'locations',
            'languages',
            'groups',
            'archives',
            'folders',
            'fonds',
            'users',
            'tags',
            'cipherKey',
            'states'
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
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyCipherKey $request, CipherKey $cipherKey)
    {
        $cipherKey->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyCipherKey $request
     * @throws Exception
     * @return Response|bool
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

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }

    /**
     * Sync cipher key images
     *
     * @param CipherKey $cipherKey
     * @param array $sanitized
     * @return void
     */
    public function syncCipherKeyImages($cipherKey, $sanitized)
    {

        foreach ($sanitized['images'] as $key => $image) {
            $img = $cipherKey->images()->create([
                'structure' => $image->structure,
                'has_instructions' => $image->has_instructions,
                'is_local' => true,
                'ordering' => 1
            ]);

            $img
                ->addMedia($sanitized['files'][$key])
                ->toMediaCollection('picture');

            $img->update(['url' => $img->getFirstMediaPath('picture')]);
        }
    }

    /**
     * Sync cipher key users
     *
     * @param CipherKey $cipherKey
     * @param array $sanitized
     * @return void
     */
    public function syncCipherKeyUsers($model, $sanitized, $mode = 'create')
    {

        //Syn ingredients to model
        if (count($sanitized['users']) > 0 || $mode == 'update') {
            $model->users()->delete();

            foreach ($sanitized['users'] as $user) {
                $newUser = $user->user;
                if (isset($user->new_user) && $user->new_user) {
                    $newUser = Person::create(['name' => $user->new_user]);
                }
                $model->users()->create([
                    'person_id' => $newUser->id,
                    'is_main_user' => $user->is_main_user
                ]);
            }
        }
        return true;
    }

    /**
     * Sync cryptograms
     *
     * @param CipherKey $key
     * @param array $sanitized
     * @return void
     */
    public function syncCryptograms($key, $sanitized)
    {
        $cryptograms = collect($sanitized['cryptograms'])->pluck('id')->toArray();
        $key->cryptograms()->sync($cryptograms);
    }


    /**
     * Get archive ID
     *
     * @param CipherKey $cipherKey
     * @param array $sanitized
     * @return void
     */
    public function syncArchive(CipherKey $cipherKey, $sanitized)
    {

        $archive_id = $sanitized['archive_id'];

        if (
            isset($sanitized['new_archive']) &&
            $sanitized['new_archive'] &&
            $sanitized['new_archive'] !== 'null'
        ) {
            $archive = Archive::create([
                'name' => $sanitized['new_archive'],
                'short_name' => $sanitized['new_archive'],
            ]);
            $archive_id = $archive->id;
        }

        $fond_id = $sanitized['fond_id'];

        if (
            isset($sanitized['new_fond']) &&
            $sanitized['new_fond'] &&
            $sanitized['new_fond'] !== 'null'
        ) {
            $fond = Fond::create([
                'name' => $sanitized['new_fond'],
                'archive_id' => $archive_id,
            ]);
            $fond_id = $fond->id;
        }

        $folder_id = $sanitized['folder_id'];

        if (
            isset($sanitized['new_folder']) &&
            $sanitized['new_folder'] &&
            $sanitized['new_folder']  !== 'null'
        ) {
            $folder = Folder::create([
                'name' => $sanitized['new_folder'],
                'fond_id' => $fond_id,
            ]);
            $folder_id = $folder->id;
        }

        if ($folder_id) {
            $cipherKey->update(['folder_id' => $folder_id]);
        }
    }

    /**
     * Sync tags
     *
     * @param CipherKey $key
     * @param array $sanitized
     * @return void
     */
    public function syncTags(CipherKey $key, $sanitized)
    {
        $tags = collect($sanitized['tags'])->pluck('id')->toArray();
        $key->tags()->sync($tags);
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

        $state = State::create([
            'name' => $cipherKey->signature,
            'state' => $sanitized['state'],
            'note' => $sanitized['note'],
            'created_by' => auth()->user()->id
        ]);

        $cipherKey->update(['state_id' => $state->id]);

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
            $results  = CipherKey::where('signature', 'LIKE', "%{$request->search}%")->orWhere('complete_structure', 'LIKE', "%{$request->search}%")->orderBy('id', 'desc')->get();
        }
        return response()->json($results);
    }
}
