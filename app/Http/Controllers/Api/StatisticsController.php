<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Category\IndexRequest;
use App\Models\CipherKey;
use App\Models\Cryptogram;
use App\Traits\ApiResponser;

/**
 * @group Statistics
 *
 * APIs for Statistics
 */
class StatisticsController extends Controller
{
    use ApiResponser;

    /**
     * Show all solutions
     *
     * @unauthenticated
     * 
     * @responseFile responses/statistics/index.200.json
     * 
     */
    public function index()
    {
        $keysCount = CipherKey::count();
        $cryptogramsCount = Cryptogram::count();

        return $this->success([
            'cipher_keys_count' => $keysCount,
            'cryptograms' => $cryptogramsCount
        ], 'Statsitics data.', 200);
    }
}
