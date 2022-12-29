<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cipher\BulkDestroyCipher;
use App\Http\Requests\Admin\Cipher\DestroyCipher;
use App\Http\Requests\Admin\Cipher\IndexCipher;
use App\Http\Requests\Admin\Cipher\StoreCipher;
use App\Http\Requests\Admin\Cipher\UpdateCipher;
use App\Models\Category;
use App\Models\Cipher;
use App\Models\Language;
use App\Models\Location;
use App\Models\Person;
use App\Models\Solution;
use App\Models\Tag;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CiphersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexCipher $request
     * @return array|Factory|View
     */
    public function index(IndexCipher $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Cipher::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['availability', 'category_id', 'day', 'flag', 'id', 'image_url', 'language_id', 'location_id', 'month', 'name', 'recipient_id', 'sender_id', 'solution_id', 'state_id', 'year'],

            // set columns to searchIn
            ['availability', 'description', 'id', 'image_url', 'name']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.cipher.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.cipher.create');

        $locations = Location::all();
        $languages = Language::all();
        $persons = Person::all();
        $tags = Tag::where('type', Tag::CIPHER)
            ->orWhereNull('type')
            ->get();
        $solutions = Solution::all();
        $categories = Category::orderBy('name', 'asc')->get();

        return view('admin.cipher.create', compact(
            'locations',
            'languages',
            'persons',
            'tags',
            'solutions',
            'categories'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCipher $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreCipher $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the Cipher
        $cipher = Cipher::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/ciphers'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/ciphers');
    }

    /**
     * Display the specified resource.
     *
     * @param Cipher $cipher
     * @throws AuthorizationException
     * @return void
     */
    public function show(Cipher $cipher)
    {
        $this->authorize('admin.cipher.show', $cipher);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Cipher $cipher
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Cipher $cipher)
    {
        $this->authorize('admin.cipher.edit', $cipher);

        $locations = Location::all();
        $languages = Language::all();
        $persons = Person::all();
        $tags = Tag::where('type', Tag::CIPHER)
            ->orWhereNull('type')
            ->get();
        $solutions = Solution::all();
        $categories = Category::orderBy('name', 'asc')->get();

        return view('admin.cipher.edit', [
            'cipher' => $cipher,
            'locations' => $locations,
            'languages' => $languages,
            'persons' => $persons,
            'tags' => $tags,
            'solutions' => $solutions,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCipher $request
     * @param Cipher $cipher
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateCipher $request, Cipher $cipher)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Cipher
        $cipher->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/ciphers'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/ciphers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyCipher $request
     * @param Cipher $cipher
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyCipher $request, Cipher $cipher)
    {
        $cipher->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyCipher $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyCipher $request): Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Cipher::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
