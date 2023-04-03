<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CipherKey;
use App\Models\Cryptogram;
use App\Traits\ApiResponser;
use Carbon\Carbon;

/**
 * @group Statistics
 *
 * APIs for Statistics
 */
class StatisticsController extends Controller
{
    use ApiResponser;

    /**
     * Show all statistics
     *
     * @unauthenticated
     * 
     * @responseFile responses/statistics/index.200.json
     * 
     */
    public function index()
    {
        //Global
        $keysCount = CipherKey::approved()->count();
        $cryptogramsCount = Cryptogram::approved()->count();

        $archivesCipherCount =  CipherKey::with('folder.fond.archive')->approved()->whereHas('folder.fond.archive')->get()->pluck('archive.name');
        $archivesCryptoCount =  Cryptogram::with('folder.fond.archive')->approved()->whereHas('folder.fond.archive')->get()->pluck('archive.name');
        $archivesCount = $archivesCipherCount->merge($archivesCryptoCount)->unique()->count();

        $cipherAndCryptoByCentury = $this->cipherAndCryptoByCentury();

        return $this->success([
            'global' => [
                'count' => [
                    'total' => $keysCount + $cryptogramsCount,
                    'cipher_keys' => $keysCount,
                    'cryptograms' => $cryptogramsCount,
                    'archives' => $archivesCount,
                ],
                'by_century' => $cipherAndCryptoByCentury,
            ],
            'cipher_keys' => [
                'count' => $keysCount,
            ],
            'cryptograms' => [
                'count' => $cryptogramsCount
            ]
        ], 'Statsitics data.', 200);
    }

    /**
     * Get cipher and cryptograms stats by century
     *
     * @return array
     */
    private function cipherAndCryptoByCentury()
    {
        $centuries = collect([]);
        $centuriesCipherDatasets = collect([]);
        $centuriesCryptoDatasets = collect([]);
        $centuryStats = collect([]);

        $actualCentury = (int) ceil(now()->year / 100);

        for ($i = 15; $i <= $actualCentury; $i++) {

            $year = $i * 100;
            $from = Carbon::createFromFormat("Y", $year)->startOfCentury()->toDateString();
            $to = Carbon::createFromFormat("Y", $year)->endOfCentury()->toDateString();

            $cipherKeyByCentury = CipherKey::whereBetween('used_from', [$from, $to])->orWhereBetween('used_to', [$from, $to])->approved()->count();
            $centuriesCipherDatasets->push($cipherKeyByCentury);

            $cryptogramByCentury = Cryptogram::whereBetween('date', [$from, $to])->approved()->count();
            $centuriesCryptoDatasets->push($cryptogramByCentury);

            $centuries->push(['title' => $i . ". century", 'century_from' => $from, 'century_to' => $to, 'cipher_count' => $cipherKeyByCentury, 'cryptograms_count' => $cryptogramByCentury]);
        }

        $cipherKeyByCentury = CipherKey::whereNull('used_from')->whereNull('used_to')->approved()->count();
        $centuriesCipherDatasets->push($cipherKeyByCentury);

        $cryptogramByCentury = Cryptogram::whereNull('date')->approved()->count();
        $centuriesCryptoDatasets->push($cryptogramByCentury);

        $centuries->push(['title' => "Not recognized", 'century_from' => null, 'century_to' => null, 'cipher_count' => $cipherKeyByCentury, 'cryptograms_count' => $cryptogramByCentury]);

        $centuryStats->push([
            'label' => 'Number of cipher keys',
            'backgroundColor' => '#fa7315',
            'borderColor' => '#fa7315',
            'data' => $centuriesCipherDatasets->toArray(),
            'fill' => false,
            'barThickness' => 8,
        ]);
        $centuryStats->push([
            'label' => 'Number of cryptograms',
            'backgroundColor' => '#344256',
            'borderColor' => '#344256',
            'data' => $centuriesCryptoDatasets->toArray(),
            'fill' => false,
            'barThickness' => 8,
        ]);

        return [
            'centuries_title' => $centuries->pluck('title')->toArray(),
            'centuries' => $centuries->toArray(),
            'datasets' => $centuryStats->toArray()
        ];
    }
}
