<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Tag\IndexRequest;
use App\Http\Resources\Tag\TagResource;
use App\Models\Tag;
use App\Traits\ApiResponser;
use App\Traits\Paginable;


/**
 * @group Tags
 *
 * APIs for Tags
 */
class TagsController extends Controller
{
    use ApiResponser;
    use Paginable;

    /**
     * Show all tags
     *
     * @authenticated
     * 
     * All tags <br><br>
     * 
     * <b>Status codes:</b><br>
     * <b>200</b> - Successfully given data<br>
     * <b>422</b> - Validation error <br>
     * 
     * 
     * @responseFile responses/tags/index.200.json
     * 
     */
    public function index(IndexRequest $request)
    {
        $tags = Tag::query();
        $sanitized = $request->getSanitized();

        if (isset($sanitized['type']) && $sanitized['type']) {
            $tags = $tags->where('type', $sanitized['type']);
        }

        $tags = $this->filterPagination($tags, $request, 'name', 'asc', false);

        return $this->success(TagResource::collection($tags), 'List of all tags', 200);
        //return $this->success(new LocationCollection($locations), 'List of all locations', 200);
    }
}
