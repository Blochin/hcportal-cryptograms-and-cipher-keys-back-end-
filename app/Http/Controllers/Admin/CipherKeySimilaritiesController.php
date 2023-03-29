<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CipherKeySimilarity\BulkDestroyCipherKeySimilarity;
use App\Http\Requests\Admin\CipherKeySimilarity\DestroyCipherKeySimilarity;
use App\Http\Requests\Admin\CipherKeySimilarity\IndexCipherKeySimilarity;
use App\Http\Requests\Admin\CipherKeySimilarity\StoreCipherKeySimilarity;
use App\Http\Requests\Admin\CipherKeySimilarity\UpdateCipherKeySimilarity;
use App\Models\CipherKey;
use App\Models\CipherKeySimilarity;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CipherKeySimilaritiesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexCipherKeySimilarity $request
     * @return array|Factory|View
     */
    public function index(IndexCipherKeySimilarity $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(CipherKeySimilarity::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'name'],

            // set columns to searchIn
            ['id', 'name'],

            function ($query) {
                $query->with('cipherKeys');
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

        return view('admin.cipher-key-similarity.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.cipher-key-similarity.create');

        $cipherKeys = CipherKey::select('id', 'name', 'complete_structure')->get();

        return view('admin.cipher-key-similarity.create', [
            'cipherKeys' =>  $cipherKeys
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCipherKeySimilarity $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreCipherKeySimilarity $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the CipherKeySimilarity
        $cipherKeySimilarity = CipherKeySimilarity::create($sanitized);

        //Sync cipher keys
        $cipherKeySimilarity->cipherKeys()->sync($sanitized['cipher_keys']);

        alert()->success('Success', 'Sucessfully added cipher key similarity.');

        if ($request->ajax()) {
            return ['redirect' => url('admin/cipher-key-similarities'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/cipher-key-similarities');
    }

    /**
     * Display the specified resource.
     *
     * @param CipherKeySimilarity $cipherKeySimilarity
     * @throws AuthorizationException
     * @return void
     */
    public function show(CipherKeySimilarity $cipherKeySimilarity)
    {
        $this->authorize('admin.cipher-key-similarity.show', $cipherKeySimilarity);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CipherKeySimilarity $cipherKeySimilarity
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(CipherKeySimilarity $cipherKeySimilarity)
    {
        $this->authorize('admin.cipher-key-similarity.edit', $cipherKeySimilarity);

        $cipherKeys = CipherKey::select('id', 'name', 'complete_structure')->get();

        $cipherKeySimilarity->load('cipherKeys');

        return view('admin.cipher-key-similarity.edit', [
            'cipherKeySimilarity' => $cipherKeySimilarity,
            'cipherKeys' => $cipherKeys
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCipherKeySimilarity $request
     * @param CipherKeySimilarity $cipherKeySimilarity
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateCipherKeySimilarity $request, CipherKeySimilarity $cipherKeySimilarity)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values CipherKeySimilarity
        $cipherKeySimilarity->update($sanitized);

        //Sync cipher keys
        $cipherKeySimilarity->cipherKeys()->sync($sanitized['cipher_keys']);

        alert()->success('Success', 'Sucessfully updated cipher key similarity.');

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/cipher-key-similarities'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/cipher-key-similarities');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyCipherKeySimilarity $request
     * @param CipherKeySimilarity $cipherKeySimilarity
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyCipherKeySimilarity $request, CipherKeySimilarity $cipherKeySimilarity)
    {
        $cipherKeySimilarity->delete();

        alert()->success('Success', 'Sucessfully deleted cipher key similarity.');

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyCipherKeySimilarity $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyCipherKeySimilarity $request): Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    CipherKeySimilarity::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        alert()->success('Success', 'Sucessfully deleted selected cipher key similarities.');

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
