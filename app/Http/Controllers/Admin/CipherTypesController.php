<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CipherType\BulkDestroyCipherType;
use App\Http\Requests\Admin\CipherType\DestroyCipherType;
use App\Http\Requests\Admin\CipherType\IndexCipherType;
use App\Http\Requests\Admin\CipherType\StoreCipherType;
use App\Http\Requests\Admin\CipherType\UpdateCipherType;
use App\Models\CipherType;
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

class CipherTypesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexCipherType $request
     * @return array|Factory|View
     */
    public function index(IndexCipherType $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(CipherType::class)->processRequestAndGet(
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

        return view('admin.cipher-type.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.cipher-type.create');

        return view('admin.cipher-type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCipherType $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreCipherType $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the CipherType
        $cipherType = CipherType::create($sanitized);

        alert()->success('Success', 'Sucessfully added cipher type.');

        if ($request->ajax()) {
            return ['redirect' => url('admin/cipher-types'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/cipher-types');
    }

    /**
     * Display the specified resource.
     *
     * @param CipherType $cipherType
     * @throws AuthorizationException
     * @return void
     */
    public function show(CipherType $cipherType)
    {
        $this->authorize('admin.cipher-type.show', $cipherType);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CipherType $cipherType
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(CipherType $cipherType)
    {
        $this->authorize('admin.cipher-type.edit', $cipherType);


        return view('admin.cipher-type.edit', [
            'cipherType' => $cipherType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCipherType $request
     * @param CipherType $cipherType
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateCipherType $request, CipherType $cipherType)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values CipherType
        $cipherType->update($sanitized);

        alert()->success('Success', 'Sucessfully updated cipher type.');

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/cipher-types'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/cipher-types');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyCipherType $request
     * @param CipherType $cipherType
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyCipherType $request, CipherType $cipherType)
    {
        $cipherType->delete();

        alert()->success('Success', 'Sucessfully deleted cipher type.');

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyCipherType $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyCipherType $request): Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    CipherType::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        alert()->success('Success', 'Sucessfully deleted selected cipher types.');

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
