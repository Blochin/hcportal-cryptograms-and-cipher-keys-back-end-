<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CipherType\CipherTypeResource;
use App\Http\Resources\KeyType\KeyTypeResource;
use App\Http\Resources\Location\LocationResource;
use App\Models\CipherType;
use App\Models\KeyType;
use App\Models\Location;
use App\Traits\ApiResponser;
use App\Traits\Paginable;


/**
 * @group Cipher keys
 *
 * APIs for Key types
 */
class CipherTypesController extends Controller
{
    use ApiResponser;
    use Paginable;

    /**
     * Show all cipher types
     *
     * @authenticated
     * 
     * Show all cipher types <br><br>
     * 
     * <b>Status codes:</b><br>
     * <b>200</b> - Successfully given data<br>
     * 
     * 
     * @responseFile responses/cipher_types/index.200.json
     * 
     */
    public function index(Request $request)
    {
        $cipherTypes = CipherType::query();

        $cipherTypes = $this->filterPagination($cipherTypes, $request, 'name', 'asc', false);

        return $this->success(CipherTypeResource::collection($cipherTypes), 'List of all cipher types.', 200);
    }
}
