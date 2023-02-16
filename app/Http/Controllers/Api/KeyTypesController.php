<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\KeyType\KeyTypeResource;
use App\Http\Resources\Location\LocationResource;
use App\Models\KeyType;
use App\Models\Location;
use App\Traits\ApiResponser;
use App\Traits\Paginable;


/**
 * @group Cipher keys
 *
 * APIs for Key types
 */
class KeyTypesController extends Controller
{
    use ApiResponser;
    use Paginable;

    /**
     * Show all key types
     *
     * @authenticated
     * 
     * Show all key types <br><br>
     * 
     * <b>Status codes:</b><br>
     * <b>200</b> - Successfully given data<br>
     * 
     * 
     * @responseFile responses/key_types/index.200.json
     * 
     */
    public function index(Request $request)
    {
        $keyTypes = KeyType::query();

        $keyTypes = $this->filterPagination($keyTypes, $request, 'name', 'asc', false);

        return $this->success(KeyTypeResource::collection($keyTypes), 'List of all key types.', 200);
    }
}
