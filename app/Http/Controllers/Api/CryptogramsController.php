<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cryptogram\ApprovedRequest;
use App\Http\Requests\Api\Cryptogram\StoreCryptogram;
use App\Http\Requests\Api\Cryptogram\UpdateCryptogram;
use App\Http\Resources\CipherKey\CipherKeyApprovedDetailedResource;
use App\Http\Resources\Cryptogram\CryptogramApprovedCollection;
use App\Http\Resources\Cryptogram\CryptogramApprovedResource;
use App\Http\Resources\Cryptogram\CryptogramDetailedResource;
use App\Mail\NewCipherKeyMail;
use App\Mail\NewCryptogramMail;
use App\Mail\UpdateCipherKeyMail;
use App\Mail\UpdateCryptogramMail;
use App\Models\CipherKey;
use App\Models\Cryptogram;
use App\Traits\ApiResponser;
use App\Traits\CipherKey\CipherKeySyncable;
use App\Traits\Cryptogram\CryptogramSyncable;
use App\Traits\Paginable;
use Illuminate\Support\Facades\Mail;

/**
 * @group Cryptograms
 *
 * APIs for Cryptograms
 */
class CryptogramsController extends Controller
{
    use ApiResponser;
    use Paginable;
    use CryptogramSyncable;
    use CipherKeySyncable;

    /**
     * All approved cryptograms
     *
     * @unauthenticated
     * 
     * Approved cryptograms <br><br>
     * 
     * <b>Status codes:</b><br>
     * <b>200</b> - Successfully given data<br>
     * 
     * 
     * @responseFile responses/cryptograms/approved.200.json
     * @responseFile responses/cryptograms/approved_detailed.200.json
     * 
     */
    public function approved(ApprovedRequest $request)
    {
        $cryptograms = Cryptogram::with([
            'sender',
            'language',
            'location',
            'recipient',
            'solution',
            'category',
            'category.children',
            'folder',
            'folder.fond',
            'folder.fond.archive',
            'tags',
            'groups',
            'groups.data',
            'submitter',
        ])->approved();

        if ($request->detailed) {
            $cryptograms = $this->filterPagination($cryptograms, $request, 'name', 'asc', true);
            return $this->success(new CryptogramApprovedCollection($cryptograms), 'List of all approved cryptograms with details.', 200);
        }

        $cryptograms = $this->filterPagination($cryptograms, $request, 'name', 'asc', false);

        return $this->success(CryptogramApprovedResource::collection($cryptograms), 'List of all approved cryptograms.', 200);
    }

    /**
     * All my cryptograms
     *
     * @authenticated
     * 
     * My cryptograms <br><br>
     * 
     * <b>Status codes:</b><br>
     * <b>200</b> - Successfully given data<br>
     * 
     * 
     * @responseFile responses/cryptograms/my.200.json
     * @responseFile responses/cryptograms/my_detailed.200.json
     * 
     */
    public function myCryptograms(ApprovedRequest $request)
    {
        $user = auth('sanctum')->user();

        $cryptograms = Cryptogram::with([
            'sender',
            'language',
            'location',
            'recipient',
            'solution',
            'category',
            'category.children',
            'folder',
            'folder.fond',
            'folder.fond.archive',
            'tags',
            'groups',
            'groups.data',
            'submitter',
        ])->where('created_by', $user->id);

        if ($request->detailed) {
            $cryptograms = $this->filterPagination($cryptograms, $request, 'name', 'asc', true);
            return $this->success(new CryptogramApprovedCollection($cryptograms), 'List of all my cryptograms with details.', 200);
        }

        $cryptograms = $this->filterPagination($cryptograms, $request, 'name', 'asc', false);

        return $this->success(CryptogramApprovedResource::collection($cryptograms), 'List of all my cryptograms.', 200);
        //return $this->success(new LocationCollection($locations), 'List of all locations', 200);
    }

    /**
     * Show cryptogram
     *
     * @unauthenticated
     * 
     * Cryptogram <br><br>
     * 
     * <b>Status codes:</b><br>
     * <b>200</b> - Successfully given data<br>
     * 
     * 
     * @responseFile responses/cryptograms/show.200.json
     * 
     */
    public function show(Cryptogram $cryptogram)
    {

        $cryptogram->load([
            'sender',
            'language',
            'location',
            'recipient',
            'solution',
            'category',
            'category.children',
            'folder',
            'folder.fond',
            'folder.fond.archive',
            'tags',
            'groups',
            'groups.data',
            'submitter',
        ]);

        if (
            auth('sanctum')->check() && $cryptogram->created_by == auth('sanctum')->user()->id ||
            $cryptogram->state['id'] == CipherKey::STATUS_APPROVED
        ) {
            return $this->success(new CryptogramDetailedResource($cryptogram), 'Get a cryptogram.', 200);
        }

        return response()->json([
            'status' => "Validation error",
            'status_code' => 422,
            'message' => "Validation error",
            'data' => 'Cryptogram is not approved or you are not a submitter.'
        ], 422);
    }

    /**
     * Create cryptogram
     *
     * @authenticated
     * 
     * @header Content-Type multipart/form-data
     * 
     * @bodyParam groups json required List of datagroups. Example: [{"description":"Links and references","data":[{"type": "link","name":"Links and references","title":"Source","text":"","link":"https://test.sk"}]},{"description":"Transcription","data":[{"type":"text","name":"Transcription","title":"Transcription","text":"text text text","link":""}]},{"description":"Cryptogram","data":[{"type":"image","name":"Cryptogram","title":"Cryptogram image","text":"","link":""}]},{"description":"Cryptogram 2","data":[{"type":"image","name":"","title":"Cryptogram 2","text":"","link":""}]}]
     * @bodyParam groups[].description string required Group name. Example: 0
     * @bodyParam groups[].data object[] required Group data Example: lol
     * @bodyParam groups.data[].type string required Group data type ['image', 'link', 'text'] Example: image
     * @bodyParam groups.data[].title string required Group data name Example: Source
     * @bodyParam groups.data[].text string required Group data text Example: Text of data
     * @bodyParam groups.data[].link string required Group data link Example: https://www.test.sk
     * @bodyParam images file[][] required Data image files [datagroup_index][data_index].
     * @bodyParam tags [] string Tag names
     * 
     * 
     * @responseFile responses/cryptograms/create.200.json
     * @responseFile responses/cryptograms/create.422.json
     * 
     */
    public function create(StoreCryptogram $request)
    {

        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the Cryptogram
        $cryptogram = Cryptogram::create($sanitized);

        //Sync thumbnail
        if (isset($sanitized['thumbnail']) && $sanitized['thumbnail']) {
            $cryptogram
                ->addMedia($sanitized['thumbnail'])
                ->toMediaCollection('picture');
        } elseif (isset($sanitized['thumbnail_link']) && $sanitized['thumbnail_link']) {
            $cryptogram
                ->addMediaFromUrl($sanitized['thumbnail_link'])
                ->toMediaCollection('picture');
        } elseif (isset($sanitized['thumbnail_base64']) && $sanitized['thumbnail_base64']) {
            $cryptogram
                ->addMediaFromBase64($sanitized['thumbnail_base64'])
                ->toMediaCollection('picture');
        }

        //Sync datagroups
        $this->syncDatagroups($cryptogram, $sanitized, 'api');

        //Sync tags
        $this->syncTagsCryptogram($cryptogram, $sanitized, 'api');

        //Store archives,fonds,folders
        $this->syncArchive($cryptogram, $sanitized, true);


        //Load relationships
        $cryptogram->load([
            'sender',
            'language',
            'location',
            'recipient',
            'solution',
            'category',
            'category.children',
            'folder',
            'folder.fond',
            'folder.fond.archive',
            'tags',
            'groups',
            'groups.data',
            'submitter',
        ]);

        Mail::to(config('mail.to.email'))->send(new NewCryptogramMail($cryptogram));

        return $this->success(new CryptogramDetailedResource($cryptogram), 'Successfully added cryptogram.', 200);
    }

    /**
     * Update cryptogram
     *
     * @authenticated
     * 
     * @header Content-Type multipart/form-data
     * 
     * @bodyParam groups json required List of datagroups. Example: [{"description":"Links and references","data":[{"type": "link","name":"Links and references","title":"Source","text":"","link":"https://test.sk"}]},{"description":"Transcription","data":[{"type":"text","name":"Transcription","title":"Transcription","text":"text text text","link":""}]},{"description":"Cryptogram","data":[{"type":"image","name":"Cryptogram","title":"Cryptogram image","text":"","link":""}]},{"description":"Cryptogram 2","data":[{"type":"image","name":"","title":"Cryptogram 2","text":"","link":""}]}]
     * @bodyParam groups[].description string required Group name. Example: 0
     * @bodyParam groups[].data object[] required Group data Example: lol
     * @bodyParam groups.data[].type string required Group data type ['image', 'link', 'text'] Example: image
     * @bodyParam groups.data[].title string required Group data name Example: Source
     * @bodyParam groups.data[].text string required Group data text Example: Text of data
     * @bodyParam groups.data[].link string required Group data link Example: https://www.test.sk
     * @bodyParam images file[][] required Data image files [datagroup_index][data_index].
     * @bodyParam tags [] string Tag names
     * 
     * 
     * @responseFile responses/cryptograms/update.200.json
     * @responseFile responses/cryptograms/update.422.json
     * 
     */
    public function update(UpdateCryptogram $request, Cryptogram $cryptogram)
    {

        $user = auth('sanctum')->user();

        //Load relationships
        $cryptogram->load([
            'sender',
            'language',
            'location',
            'recipient',
            'solution',
            'category',
            'category.children',
            'folder',
            'folder.fond',
            'folder.fond.archive',
            'tags',
            'groups',
            'groups.data',
            'submitter',
        ]);

        //Check if submitter id is not equal to logged user
        if ($cryptogram->submitter->id != $user->id) {
            return $this->success(['submitter' => ["You cannot edit cryptogram of another user."]], 'Validation error.', 422, 422);
        }

        //Check if submitter id is not equal to logged user
        if (
            $cryptogram->state['id'] == CipherKey::STATUS_REJECTED
        ) {
            return $this->success(['state' => ["You cannot edit cryptogram in another state as APPROVED, AWAITING or REVISE."]], 'Validation error.', 422, 422);
        }

        // Sanitize input
        $sanitized = $request->getSanitized();

        if (isset($sanitized['note'])) {
            $sanitized['note'] = $cryptogram->note . "\n" . $sanitized['note'];
        }

        // Update changed values the Cryptogram
        $cryptogram->update($sanitized);

        //Sync thumbnail
        if (isset($sanitized['thumbnail']) && $sanitized['thumbnail']) {
            $cryptogram
                ->addMedia($sanitized['thumbnail'])
                ->toMediaCollection('picture');
        } elseif (isset($sanitized['thumbnail_link']) && $sanitized['thumbnail_link']) {
            $cryptogram
                ->addMediaFromUrl($sanitized['thumbnail_link'])
                ->toMediaCollection('picture');
        } elseif (isset($sanitized['thumbnail_base64']) && $sanitized['thumbnail_base64']) {
            $cryptogram
                ->addMediaFromBase64($sanitized['thumbnail_base64'])
                ->toMediaCollection('picture');
        }

        //Sync datagroups
        $this->syncDatagroups($cryptogram, $sanitized, 'api');

        //Sync tags
        $this->syncTagsCryptogram($cryptogram, $sanitized, 'api');

        //Store archives,fonds,folders
        $this->syncArchive($cryptogram, $sanitized, true);


        Mail::to(config('mail.to.email'))->send(new UpdateCryptogramMail($cryptogram));

        $cryptogram = Cryptogram::with([
            'sender',
            'language',
            'location',
            'recipient',
            'solution',
            'category',
            'category.children',
            'folder',
            'folder.fond',
            'folder.fond.archive',
            'tags',
            'groups',
            'groups.data',
            'submitter',
        ])->findOrFail($cryptogram->id);

        return $this->success(new CryptogramDetailedResource($cryptogram), 'Successfully updated cryptogram.', 200);
    }
}
