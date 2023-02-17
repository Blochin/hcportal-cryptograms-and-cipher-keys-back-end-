<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Archive\IndexRequest as ArchiveIndexRequest;
use App\Http\Resources\Tag\TagResource;
use App\Models\Archive;
use App\Models\Tag;
use App\Traits\ApiResponser;
use App\Traits\Paginable;


/**
 * @group Archives
 *
 * APIs for Archives
 */
class ArchivesController extends Controller
{
    use ApiResponser;
    use Paginable;

    /**
     * Show all archives
     *
     * @authenticated
     * 
     * 
     * @responseFile responses/archives/index.200.json
     * 
     */
    public function index(ArchiveIndexRequest $request)
    {
        $archives = Archive::with(['fonds', 'fonds.folders']);
        $sanitized = $request->getSanitized();

        $archives = $this->filterPagination($archives, $request, 'name', 'asc', false);

        return $this->success($archives, 'List of all archives.', 200);
    }
}
