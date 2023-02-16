<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CipherKey\ApprovedRequest;
use App\Http\Resources\CipherKey\CipherKeyApprovedCollection;
use App\Http\Resources\CipherKey\CipherKeyApprovedDetailedResource;
use App\Http\Resources\CipherKey\CipherKeyApprovedResource;
use App\Http\Resources\Location\LocationResource;
use App\Models\CipherKey;
use App\Models\Location;
use App\Traits\ApiResponser;
use App\Traits\Paginable;


/**
 * @group Cipher keys
 *
 * APIs for Cipher keys
 */
class CipherKeysController extends Controller
{
    use ApiResponser;
    use Paginable;

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
            'language',
            'location',
            'tags'
        ])->where('created_by', $user->id);

        if ($request->detailed) {
            $cipherKeys = $this->filterPagination($cipherKeys, $request, 'signature', 'asc', true);
            return $this->success(new CipherKeyApprovedCollection($cipherKeys), 'List of all approved cipher keys with details.', 200);
        }

        $cipherKeys = $this->filterPagination($cipherKeys, $request, 'signature', 'asc', false);

        return $this->success(CipherKeyApprovedResource::collection($cipherKeys), 'List of all approved cipher keys.', 200);
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
            'language',
            'location',
            'tags'
        ]);


        return $this->success(new CipherKeyApprovedDetailedResource($cipherKey), 'Get a cipher key.', 200);
    }
}
