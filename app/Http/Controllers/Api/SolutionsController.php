<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Archive\IndexRequest as ArchiveIndexRequest;
use App\Http\Requests\Api\Category\IndexRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Solution\SolutionResource;
use App\Http\Resources\Tag\TagResource;
use App\Models\Archive;
use App\Models\Category;
use App\Models\Solution;
use App\Models\Tag;
use App\Traits\ApiResponser;
use App\Traits\Paginable;


/**
 * @group Solutions
 *
 * APIs for Solutions
 */
class SolutionsController extends Controller
{
    use ApiResponser;
    use Paginable;

    /**
     * Show all solutions
     *
     * @authenticated
     * 
     * @responseFile responses/solutions/index.200.json
     * 
     */
    public function index(IndexRequest $request)
    {
        $solutions = Solution::query();

        $solutions = $this->filterPagination($solutions, $request, 'name', 'asc', false);

        return $this->success(SolutionResource::collection($solutions), 'List of all solutions.', 200);
    }
}
