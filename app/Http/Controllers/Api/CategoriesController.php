<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Archive\IndexRequest as ArchiveIndexRequest;
use App\Http\Requests\Api\Category\IndexRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Tag\TagResource;
use App\Models\Archive;
use App\Models\Category;
use App\Models\Tag;
use App\Traits\ApiResponser;
use App\Traits\Paginable;


/**
 * @group Categories
 *
 * APIs for Categories
 */
class CategoriesController extends Controller
{
    use ApiResponser;
    use Paginable;

    /**
     * Show all categories
     *
     * @authenticated
     *
     * @responseFile responses/categories/index.200.json
     *
     */
    public function index(IndexRequest $request)
    {
        $categories = Category::with(['children', 'parent']);

        $categories = $this->filterPagination($categories, $request, 'name', 'asc', false);

        return $this->success(CategoryResource::collection($categories), 'List of all categories.', 200);
    }
}
