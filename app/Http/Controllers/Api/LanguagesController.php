<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Language\IndexRequest;
use App\Http\Resources\Language\LanguageResource;
use App\Models\Language;
use App\Traits\ApiResponser;
use App\Traits\Paginable;


/**
 * @group Languages
 *
 * APIs for Languages
 */
class LanguagesController extends Controller
{
    use ApiResponser;
    use Paginable;

    /**
     * Show all languages
     *
     * @authenticated
     * 
     * All languages <br><br>
     * 
     * <b>Status codes:</b><br>
     * <b>200</b> - Successfully given data<br>
     * <b>422</b> - Validation error <br>
     * 
     * 
     * @responseFile responses/languages/index.200.json
     * 
     */
    public function index(IndexRequest $request)
    {
        $languages = Language::query();

        $languages = $this->filterPagination($languages, $request, 'name', 'asc', false);

        return $this->success(LanguageResource::collection($languages), 'List of all languages', 200);
    }
}
