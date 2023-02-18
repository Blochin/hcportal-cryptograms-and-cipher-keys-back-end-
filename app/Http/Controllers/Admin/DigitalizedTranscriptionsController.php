<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DigitalizedTranscription\BulkDestroyDigitalizedTranscription;
use App\Http\Requests\Admin\DigitalizedTranscription\DestroyDigitalizedTranscription;
use App\Http\Requests\Admin\DigitalizedTranscription\IndexDigitalizedTranscription;
use App\Http\Requests\Admin\DigitalizedTranscription\StoreDigitalizedTranscription;
use App\Http\Requests\Admin\DigitalizedTranscription\UpdateDigitalizedTranscription;
use App\Models\CipherKey;
use App\Models\DigitalizedTranscription;
use App\Models\EncryptionPair;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DigitalizedTranscriptionsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexDigitalizedTranscription $request
     * @return array|Factory|View
     */
    public function index(IndexDigitalizedTranscription $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(DigitalizedTranscription::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'cipher_key_id', 'digitalized_version', 'digitalization_date', 'created_by'],

            // set columns to searchIn
            ['id', 'digitalized_version', 'note'],

            function (Builder $query) {
                $query->with(['encryptionPairs', 'cipherKey', 'submitter']);
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

        return view('admin.digitalized-transcription.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {


        $this->authorize('admin.digitalized-transcription.create');

        return view('admin.digitalized-transcription.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDigitalizedTranscription $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreDigitalizedTranscription $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the DigitalizedTranscription
        $digitalizedTranscription = DigitalizedTranscription::create($sanitized);

        //Sync encryption pairs
        $this->syncEncryptionPairs($digitalizedTranscription, $sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/digitalized-transcriptions'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/digitalized-transcriptions');
    }

    /**
     * Display the specified resource.
     *
     * @param DigitalizedTranscription $digitalizedTranscription
     * @throws AuthorizationException
     * @return void
     */
    public function show(DigitalizedTranscription $digitalizedTranscription)
    {
        $this->authorize('admin.digitalized-transcription.show', $digitalizedTranscription);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param DigitalizedTranscription $digitalizedTranscription
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(DigitalizedTranscription $digitalizedTranscription)
    {
        $this->authorize('admin.digitalized-transcription.edit', $digitalizedTranscription);

        $digitalizedTranscription->load(['encryptionPairs', 'cipherKey']);


        //Prepare data
        $mappedPairs = $digitalizedTranscription->encryptionPairs->map(function ($encryptionPair, $key) {
            return [$encryptionPair->plain_text_unit, $encryptionPair->cipher_text_unit];
        })->toArray();

        unset($digitalizedTranscription->encryptionPairs);
        $digitalizedTranscription->encryption_pairs = $mappedPairs;


        return view('admin.digitalized-transcription.edit', [
            'digitalizedTranscription' => $digitalizedTranscription,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateDigitalizedTranscription $request
     * @param DigitalizedTranscription $digitalizedTranscription
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateDigitalizedTranscription $request, DigitalizedTranscription $digitalizedTranscription)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values DigitalizedTranscription
        $digitalizedTranscription->update($sanitized);

        //Sync encryption pairs
        $this->syncEncryptionPairs($digitalizedTranscription, $sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/digitalized-transcriptions'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/digitalized-transcriptions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyDigitalizedTranscription $request
     * @param DigitalizedTranscription $digitalizedTranscription
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyDigitalizedTranscription $request, DigitalizedTranscription $digitalizedTranscription)
    {
        $digitalizedTranscription->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyDigitalizedTranscription $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyDigitalizedTranscription $request): Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    DigitalizedTranscription::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }

    /**
     * Sync encryption pairs
     *
     * @param DigitalizedTranscription  $digitalizedTranscription
     * @param array $sanitized
     * @return void
     */
    private function syncEncryptionPairs(DigitalizedTranscription $digitalizedTranscription, $sanitized)
    {
        if (!isset($sanitized['encryption_pairs'])) return;

        foreach ($sanitized['encryption_pairs'] as $pair) {
            $digitalizedTranscription->encryptionPairs()->updateOrCreate([
                'plain_text_unit' => $pair[0],
                'cipher_text_unit' => $pair[1],
            ]);
        }
    }
}
