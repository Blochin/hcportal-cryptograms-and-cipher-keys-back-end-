<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Location\LocationResource;
use App\Models\Location;
use App\Traits\ApiResponser;
use App\Traits\Paginable;


/**
 * @group Locations
 *
 * APIs for Locations
 */
class LocationsController extends Controller
{
    use ApiResponser;
    use Paginable;

    /**
     * Show all locations
     *
     * @authenticated
     * 
     * All locations <br><br>
     * 
     * <b>Status codes:</b><br>
     * <b>200</b> - Successfully given data<br>
     * 
     * 
     * @responseFile responses/locations/index.200.json
     * 
     */
    public function index(Request $request)
    {
        $locations = Location::query();

        $locations = $this->filterPagination($locations, $request, 'continent', 'asc', false);

        return $this->success(LocationResource::collection($locations), 'List of all locations', 200);
        //return $this->success(new LocationCollection($locations), 'List of all locations', 200);
    }

    /**
     * Show all continents
     *
     * @authenticated
     * 
     * All continents <br><br>
     * 
     * <b>Status codes:</b><br>
     * <b>200</b> - Successfully given data<br>
     * 
     * 
     * @responseFile responses/locations/continents.200.json
     * 
     */
    public function continents(Request $request)
    {
        $continents = collect(Location::CONTINENTS);

        return $this->success($continents, 'List of all continents', 200);
        //return $this->success(new LocationCollection($locations), 'List of all locations', 200);
    }
}
