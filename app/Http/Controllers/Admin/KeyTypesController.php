<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\KeyType\BulkDestroyKeyType;
use App\Http\Requests\Admin\KeyType\DestroyKeyType;
use App\Http\Requests\Admin\KeyType\IndexKeyType;
use App\Http\Requests\Admin\KeyType\StoreKeyType;
use App\Http\Requests\Admin\KeyType\UpdateKeyType;
use App\Models\KeyType;
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

class KeyTypesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexKeyType $request
     * @return array|Factory|View
     */
    public function index(IndexKeyType $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(KeyType::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'name'],

            // set columns to searchIn
            ['id', 'name']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.key-type.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.key-type.create');

        return view('admin.key-type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreKeyType $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreKeyType $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the KeyType
        $keyType = KeyType::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/key-types'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/key-types');
    }

    /**
     * Display the specified resource.
     *
     * @param KeyType $keyType
     * @throws AuthorizationException
     * @return void
     */
    public function show(KeyType $keyType)
    {
        $this->authorize('admin.key-type.show', $keyType);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param KeyType $keyType
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(KeyType $keyType)
    {
        $this->authorize('admin.key-type.edit', $keyType);


        return view('admin.key-type.edit', [
            'keyType' => $keyType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateKeyType $request
     * @param KeyType $keyType
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateKeyType $request, KeyType $keyType)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values KeyType
        $keyType->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/key-types'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/key-types');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyKeyType $request
     * @param KeyType $keyType
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyKeyType $request, KeyType $keyType)
    {
        $keyType->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyKeyType $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyKeyType $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    KeyType::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
