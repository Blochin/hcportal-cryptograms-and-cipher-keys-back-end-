<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CipherKey\ApprovedRequest;
use App\Http\Requests\Api\CipherKey\StoreCipherKey;
use App\Http\Requests\Api\CipherKey\UpdateCipherKey;
use App\Http\Resources\CipherKey\CipherKeyApprovedCollection;
use App\Http\Resources\CipherKey\CipherKeyApprovedDetailedResource;
use App\Http\Resources\CipherKey\CipherKeyApprovedResource;
use App\Http\Resources\Location\LocationResource;
use App\Mail\NewCipherKeyMail;
use App\Mail\UpdateCipherKeyMail;
use App\Models\CipherKey;
use App\Models\Location;
use App\Traits\ApiResponser;
use App\Traits\CipherKey\CipherKeySyncable;
use App\Traits\Paginable;
use Illuminate\Support\Facades\Mail;

/**
 * @group Cipher keys
 *
 * APIs for Cipher keys
 */
class CipherKeysController extends Controller
{
    use ApiResponser;
    use Paginable;
    use CipherKeySyncable;

    /**
     * All approved cipher keys
     *
     * @unauthenticated
     * 
     * Approved cipher keys <br><br>
     * 
     * <b>Status codes:</b><br>
     * <b>200</b> - Successfully given data<br>
     * 
     * 
     * @responseFile responses/cipher_keys/approved.200.json
     * @responseFile responses/cipher_keys/approved_detailed.200.json
     * 
     */
    public function approved(ApprovedRequest $request)
    {
        $cipherKeys = CipherKey::with([
            'images',
            'users',
            'users.person',
            'submitter',
            'cipherType',
            'keyType',
            'group',
            'folder',
            'folder.fond',
            'folder.fond.archive',
            'digitalizedTranscriptions',
            'digitalizedTranscriptions.encryptionPairs',
            'language',
            'location',
            'tags'
        ])->approved();

        if ($request->detailed) {
            $cipherKeys = $this->filterPagination($cipherKeys, $request, 'signature', 'asc', true);
            return $this->success(new CipherKeyApprovedCollection($cipherKeys), 'List of all approved cipher keys with details.', 200);
        }

        $cipherKeys = $this->filterPagination($cipherKeys, $request, 'signature', 'asc', false);

        return $this->success(CipherKeyApprovedResource::collection($cipherKeys), 'List of all approved cipher keys.', 200);
    }

    /**
     * All my cipher keys
     *
     * @authenticated
     * 
     * My cipher keys <br><br>
     * 
     * <b>Status codes:</b><br>
     * <b>200</b> - Successfully given data<br>
     * 
     * 
     * @responseFile responses/cipher_keys/my.200.json
     * @responseFile responses/cipher_keys/my_detailed.200.json
     * 
     */
    public function myKeys(ApprovedRequest $request)
    {
        $user = auth('sanctum')->user();

        $cipherKeys = CipherKey::with([
            'images',
            'users',
            'users.person',
            'submitter',
            'cipherType',
            'keyType',
            'group',
            'folder',
            'folder.fond',
            'folder.fond.archive',
            'digitalizedTranscriptions',
            'digitalizedTranscriptions.encryptionPairs',
            'language',
            'location',
            'tags'
        ])->where('created_by', $user->id);

        if ($request->detailed) {
            $cipherKeys = $this->filterPagination($cipherKeys, $request, 'signature', 'asc', true);
            return $this->success(new CipherKeyApprovedCollection($cipherKeys), 'List of all my cipher keys with details.', 200);
        }

        $cipherKeys = $this->filterPagination($cipherKeys, $request, 'signature', 'asc', false);

        return $this->success(CipherKeyApprovedResource::collection($cipherKeys), 'List of all my cipher keys.', 200);
        //return $this->success(new LocationCollection($locations), 'List of all locations', 200);
    }

    /**
     * Show cipher key
     *
     * @unauthenticated
     * 
     * Cipher key <br><br>
     * 
     * <b>Status codes:</b><br>
     * <b>200</b> - Successfully given data<br>
     * 
     * 
     * @responseFile responses/cipher_keys/show.200.json
     * 
     */
    public function show(CipherKey $cipherKey)
    {

        $cipherKey->load([
            'images',
            'users',
            'users.person',
            'submitter',
            'cipherType',
            'keyType',
            'group',
            'folder',
            'folder.fond',
            'folder.fond.archive',
            'digitalizedTranscriptions',
            'digitalizedTranscriptions.encryptionPairs',
            'language',
            'location',
            'tags'
        ]);


        return $this->success(new CipherKeyApprovedDetailedResource($cipherKey), 'Get a cipher key.', 200);
    }

    /**
     * Create cipher key
     *
     * @authenticated
     * 
     * @header Content-Type multipart/form-data
     * 
     * @bodyParam images json required List of images data. Example: [{"has_instructions": true, "structure": "example structure"},{"has_instructions": false, "structure": ""}]
     * @bodyParam images.has_instructions boolean required Image has instructions. Example: 0
     * @bodyParam images.structure string optional Image structure string Image structure Example: lol
     * @bodyParam users json required List of images data. Example: [{"name":"new user name","is_main_user":false},{"name":"new user name 2","is_main_user":true}]
     * @bodyParam users.name string required User name. Example: Helen Wisley
     * @bodyParam users.is_main_user boolean required Is main user Example: true
     * @bodyParam files[] file required Image files
     * @bodyParam tags[] string Tag names
     * 
     * 
     * @responseFile responses/cipher_keys/create.200.json
     * @responseFile responses/cipher_keys/create.422.json
     * 
     */
    public function create(StoreCipherKey $request)
    {

        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the CipherKey
        $cipherKey = CipherKey::create($sanitized);

        //Store CipherKey Images
        $this->syncCipherKeyImages($cipherKey, $sanitized);

        //Store CipherKey Users
        $this->syncCipherKeyUsers($cipherKey, $sanitized, 'create', 'api');

        //Store archives,fonds,folders
        $this->syncArchive($cipherKey, $sanitized, true);

        //Sync tags
        $this->syncTags($cipherKey, $sanitized, 'api', 'cipher_key');

        //Load relationships
        $cipherKey->load([
            'images',
            'users',
            'users.person',
            'submitter',
            'cipherType',
            'keyType',
            'group',
            'folder',
            'folder.fond',
            'folder.fond.archive',
            'digitalizedTranscriptions',
            'digitalizedTranscriptions.encryptionPairs',
            'language',
            'location',
            'tags'
        ]);

        Mail::to(config('mail.to.email'))->send(new NewCipherKeyMail($cipherKey));

        return $this->success(new CipherKeyApprovedDetailedResource($cipherKey), 'Successfully added cipher key.', 200);
    }

    /**
     * Update cipher key
     *
     * Update a cipher key is possible when the cipher key has one of the states: APPROVED, AWAITING, REVISE
     * 
     * @authenticated
     * 
     * @header Content-Type multipart/form-data
     * 
     * @bodyParam users json required List of images data. Example: [{"name":"new user name","is_main_user":false},{"name":"new user name 2","is_main_user":true}]
     * @bodyParam users.name string required User name. Example: Helen Wisley
     * @bodyParam users.is_main_user boolean required Is main user Example: true
     * @bodyParam tags[] string Tag names
     * 
     * 
     * @responseFile responses/cipher_keys/update.200.json
     * @responseFile responses/cipher_keys/update.422.json
     * 
     */
    public function update(UpdateCipherKey $request, CipherKey $cipherKey)
    {

        $user = auth('sanctum')->user();

        //Load relationships
        $cipherKey->load([
            'images',
            'users',
            'users.person',
            'submitter',
            'cipherType',
            'keyType',
            'group',
            'folder',
            'folder.fond',
            'folder.fond.archive',
            'digitalizedTranscriptions',
            'digitalizedTranscriptions.encryptionPairs',
            'language',
            'location',
            'tags'
        ]);

        //Check if submitter id is not equal to logged user
        if ($cipherKey->submitter->id != $user->id) {
            return $this->success(['submitter' => ["You cannot edit cipher key of another user."]], 'Validation error.', 422, 422);
        }

        //Check if submitter id is not equal to logged user
        if (
            $cipherKey->state['id'] == CipherKey::STATUS_REJECTED
        ) {
            return $this->success(['state' => ["You cannot edit cipher key in another state as APPROVED, AWAITING or REVISE."]], 'Validation error.', 422, 422);
        }

        // Sanitize input
        $sanitized = $request->getSanitized();

        if (isset($sanitized['note'])) {
            $sanitized['note'] = $cipherKey->note . "\n" . $sanitized['note'];
        }

        // Update changed values CipherKey
        $cipherKey->update($sanitized);

        //Store CipherKey Users
        $this->syncCipherKeyUsers($cipherKey, $sanitized, 'update', 'api');

        //Store archives,fonds,folders
        $this->syncArchive($cipherKey, $sanitized, true);

        //Sync tags
        $this->syncTags($cipherKey, $sanitized, 'api', 'cipher_key');


        Mail::to(config('mail.to.email'))->send(new UpdateCipherKeyMail($cipherKey));

        $cipherKey = CipherKey::with([
            'images',
            'users',
            'users.person',
            'submitter',
            'cipherType',
            'keyType',
            'group',
            'folder',
            'folder.fond',
            'folder.fond.archive',
            'language',
            'location',
            'tags'
        ])->findOrFail($cipherKey->id);

        return $this->success(new CipherKeyApprovedDetailedResource($cipherKey), 'Successfully updated cipher key.', 200);
    }
}
