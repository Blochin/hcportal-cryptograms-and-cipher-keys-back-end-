<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Person\IndexRequest;
use App\Http\Resources\Person\PersonResource;
use App\Models\Person;
use App\Traits\ApiResponser;
use App\Traits\Paginable;


/**
 * @group Persons
 *
 * APIs for Persons
 */
class PersonsController extends Controller
{
    use ApiResponser;
    use Paginable;

    /**
     * Show all persons
     *
     * @authenticated
     * 
     * All persons <br><br>
     * 
     * <b>Status codes:</b><br>
     * <b>200</b> - Successfully given data<br>
     * <b>422</b> - Validation error <br>
     * 
     * 
     * @responseFile responses/persons/index.200.json
     * 
     */
    public function index(IndexRequest $request)
    {
        $persons = Person::query();

        $persons = $this->filterPagination($persons, $request, 'name', 'asc', false);

        return $this->success(PersonResource::collection($persons), 'List of all persons', 200);
        //return $this->success(new LocationCollection($locations), 'List of all locations', 200);
    }
}
