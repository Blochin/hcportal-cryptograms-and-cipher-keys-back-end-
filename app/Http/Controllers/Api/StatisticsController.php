<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CipherKey;
use App\Models\CipherKeyPerson;
use App\Models\Cryptogram;
use App\Models\Language;
use App\Models\Location;
use App\Models\Person;
use App\Models\Solution;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * @group Statistics
 *
 * APIs for Statistics
 */
class StatisticsController extends Controller
{
    use ApiResponser;

    public const CENTURIES = 15;
    public const COLORS = ['#fa7315', '#29B6F6', '#2E7D32', '#FDD835', '#E91E63',  '#6A1B9A'];
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
        $cipherAndCryptoByContinent = $this->cipherAndCryptoByContinent();
        $cipherAndCryptoByLanguage = $this->cipherAndCryptoByLanguage();

        //Cipher keys
        $persons = Person::withCount(['cipherKeys' => function ($query) {
            $query->approved();
        }])->whereHas('cipherKeys', function (Builder $query) {
            $query->approved();
        })->get();

        $personsCount = $persons->count();
        $topPersons = $persons->sortByDesc('cipher_keys_count')->mapWithKeys(function ($item, $key) {
            return [$item['name'] => $item['cipher_keys_count']];
        })->take(10)->toArray();


        $topPersons = collect($topPersons)
            ->sortByDesc(function ($value, $key) {
                return $value;
            })
            ->map(function ($value, $key) {
                return [
                    'id' => $value + 1,
                    'title' => $key,
                    'cipher_count' => $value,
                ];
            })
            ->values()
            ->toArray();

        $oldest =  CipherKey::whereNotNull('used_from')->orderBy('used_from', 'asc')->orderBy('used_to', 'asc')->approved()->first();
        $newest =  CipherKey::whereNotNull('used_from')->orderBy('used_from', 'desc')->orderBy('used_to', 'desc')->approved()->first();
        $usedChars = ['L', 'S', 'N', 'D', 'M', 'G'];

        foreach ($usedChars as $char) {
            $usedChar[$char] = CipherKey::where('used_chars', 'like', "%$char%")->approved()->count();
        }

        $cipherByCentury = $this->cipherSymbolByCentury();


        //Cryptograms
        $personsCryptograms = Person::withCount(['senderCryptograms' => function ($query) {
            $query->approved();
        }, 'recipientCryptograms' => function ($query) {
            $query->approved();
        }])->get();

        $personsCryptogramsCount = $personsCryptograms->count();

        $topPersonsCryptograms = $personsCryptograms->sortByDesc(function ($person) {
            return $person->sender_cryptograms_count + $person->recipient_cryptograms_count;
        })->mapWithKeys(function ($item, $key) {
            return [$item['name'] => $item['sender_cryptograms_count'] + $item['recipient_cryptograms_count']];
        })->take(10)->toArray();

        $topPersonsCryptograms = collect($topPersonsCryptograms)
            ->sortByDesc(function ($value, $key) {
                return $value;
            })
            ->map(function ($value, $key) {
                return [
                    'title' => $key,
                    'cipher_count' => $value,
                ];
            })
            ->values()
            ->toArray();


        $senders = Person::whereHas('senderCryptograms', function (Builder $query) {
            $query->approved();
        })->count();
        $recipients = Person::whereHas('recipientCryptograms', function (Builder $query) {
            $query->approved();
        })->count();

        $total = Person::whereHas('recipientCryptograms', function (Builder $query) {
            $query->approved();
        })->orWhereHas('senderCryptograms', function (Builder $query) {
            $query->approved();
        })->count();


        $oldestCrypto =  Cryptogram::whereNotNull('date')->orderBy('date', 'asc')->approved()->first();
        $newestCrypto =  Cryptogram::whereNotNull('date')->orderBy('date', 'desc')->approved()->first();


        $cryptoByCentury = $this->cryptoBySolutions();
        $cryptoByContinent = $this->cryptoByContinent();
        $cryptoBySymbol = $this->cryptoSymbolByCentury();

        return $this->success([
            'global' => [
                'count' => [
                    'total' => $keysCount + $cryptogramsCount,
                    'cipher_keys' => $keysCount,
                    'cryptograms' => $cryptogramsCount,
                    'archives' => $archivesCount,
                ],
                'by_century' => $cipherAndCryptoByCentury,
                'by_continent' => $cipherAndCryptoByContinent,
                'by_language' => $cipherAndCryptoByLanguage,
            ],
            'cipher_keys' => [
                'count' => [
                    'persons' => $personsCount,
                    'archives' => $archivesCipherCount->count(),
                    'oldest' => $oldest && $oldest->used_from ? $oldest->used_from->format('d. m. Y') : null,
                    'newest' => $newest && $newest->used_from ? $newest->used_from->format('d. m. Y') : null,
                ],
                'by_persons' => $topPersons,
                'by_century' => $cipherByCentury
            ],
            'cryptograms' => [
                'count' => [
                    'persons' => $total,
                    'recipients' => $recipients,
                    'senders' => $senders,
                    'archives' => $archivesCryptoCount->count(),
                    'oldest' => $oldestCrypto && $oldestCrypto->date ? $oldestCrypto->date->format('d. m. Y') : null,
                    'newest' => $newestCrypto && $newestCrypto->date ? $newestCrypto->date->format('d. m. Y') : null,
                ],
                'by_persons' => $topPersons,
                'by_century' => $cryptoByCentury,
                'by_continent' => $cryptoByContinent,
                'by_symbols' => $cryptoBySymbol
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

    /**
     * Get cipher and cryptograms stats by continent
     *
     * @return array
     */
    private function cipherAndCryptoByContinent()
    {
        $continents = collect(Location::CONTINENTS);
        $continentsCount = collect([]);
        $keysDatasets = collect([]);
        $cryptoDatasets = collect([]);
        $stats = collect([]);

        foreach ($continents as $continent) {
            $cryptograms = Cryptogram::whereHas('location', function (Builder $query) use ($continent) {
                $query->where('continent', $continent['name']);
            })->approved()->count();
            $cryptoDatasets->push($cryptograms);

            $keys = CipherKey::whereHas('location', function (Builder $query) use ($continent) {
                $query->where('continent', $continent['name']);
            })->approved()->count();
            $keysDatasets->push($keys);

            $continentsCount->push(['title' => $continent['name'], 'cipher_count' => $keys, 'cryptograms_count' => $cryptograms]);
        }

        $stats->push([
            'label' => 'Number of cipher keys',
            'backgroundColor' => '#fa7315',
            'borderColor' => '#fa7315',
            'data' => $keysDatasets->toArray(),
            'fill' => false,
            'barThickness' => 8,
        ]);
        $stats->push([
            'label' => 'Number of cryptograms',
            'backgroundColor' => '#344256',
            'borderColor' => '#344256',
            'data' => $cryptoDatasets->toArray(),
            'fill' => false,
            'barThickness' => 8,
        ]);

        return [
            'continents_title' => $continents->pluck('name')->toArray(),
            'continents' => $continentsCount->toArray(),
            'datasets' => $stats->toArray()
        ];
    }

    /**
     * Get cipher and cryptograms stats by language
     *
     * @return array
     */
    private function cipherAndCryptoByLanguage()
    {
        $languages = Language::all();
        $languagesCount = collect([]);
        $keysDatasets = collect([]);
        $cryptoDatasets = collect([]);
        $stats = collect([]);

        foreach ($languages as $language) {
            $cryptograms = Cryptogram::whereHas('language', function (Builder $query) use ($language) {
                $query->where('name', $language->name);
            })->approved()->count();
            $cryptoDatasets->push($cryptograms);

            $keys = CipherKey::whereHas('language', function (Builder $query) use ($language) {
                $query->where('name', $language->name);
            })->approved()->count();

            $keysDatasets->push($keys);

            $languagesCount->push(['title' => $language->name, 'cipher_count' => $keys, 'cryptograms_count' => $cryptograms]);
        }

        $stats->push([
            'label' => 'Number of cipher keys',
            'backgroundColor' => '#fa7315',
            'borderColor' => '#fa7315',
            'data' => $keysDatasets->toArray(),
            'fill' => false,
            'barThickness' => 8,
        ]);
        $stats->push([
            'label' => 'Number of cryptograms',
            'backgroundColor' => '#344256',
            'borderColor' => '#344256',
            'data' => $cryptoDatasets->toArray(),
            'fill' => false,
            'barThickness' => 8,
        ]);

        return [
            'languages_title' => $languages->pluck('name')->toArray(),
            'languages' => $languagesCount->toArray(),
            'datasets' => $stats->toArray()
        ];
    }


    /**
     * Get cipher symbols stats by century
     *
     * @return array
     */
    private function cipherSymbolByCentury()
    {
        $centuries = collect([]);
        $centuriesRows = [];
        $centuriesCipherDatasets = collect([]);
        $centuryStats = collect([]);
        $centuryTitles = collect([]);
        $usedChars = ['L' => '#fa7315', 'S' => '#29B6F6', 'N' => '#2E7D32', 'D' => '#FDD835', 'M' => '#E91E63', 'G' => '#6A1B9A'];

        $actualCentury = (int) ceil(now()->year / 100);

        foreach ($usedChars as $char => $color) {

            $centuries[$char] = collect([]);

            for ($i = 15; $i <= $actualCentury; $i++) {

                $year = $i * 100;
                $from = Carbon::createFromFormat("Y", $year)->startOfCentury()->toDateString();
                $to = Carbon::createFromFormat("Y", $year)->endOfCentury()->toDateString();

                $cipherKeyByCentury = CipherKey::whereBetween('used_from', [$from, $to])->orWhereBetween('used_to', [$from, $to])->approved()->where('used_chars', 'like', "%$char%")->count();
                $centuriesCipherDatasets->push($cipherKeyByCentury);

                $centuries[$char]->push($cipherKeyByCentury);
                $centuriesRows[$i . ". century"][$char] = $cipherKeyByCentury;
            }


            $cipherKeyByCentury = CipherKey::whereNull('used_from')->whereNull('used_to')->approved()->where('used_chars', 'like', "%$char%")->count();
            $centuriesCipherDatasets->push($cipherKeyByCentury);

            $centuries[$char]->push($cipherKeyByCentury);
            $centuriesRows['Not recognized'][$char] = $cipherKeyByCentury;

            $centuries[$char] = $centuries[$char]->toArray();

            $centuryStats->push([
                'label' => $char,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'data' => $centuriesCipherDatasets->toArray(),
                'fill' => false,
                'barThickness' => 8,
            ]);

            $centuriesCipherDatasets = collect([]);
        }

        for ($i = 15; $i <= $actualCentury; $i++) {
            $centuryTitles->push(['title' => $i . ". century"]);
        }
        $centuryTitles->push(['title' => "Not recognized"]);

        return [
            'centuries_title' => $centuryTitles->pluck('title')->toArray(),
            'centuries' => $centuries,
            'datasets' => $centuryStats->toArray(),
            'centuriesRows' => $centuriesRows
        ];
    }


    /**
     * Get cipher symbols stats by century
     *
     * @return array
     */
    private function cryptoBySolutions()
    {
        $centuries = collect([]);
        $centuriesRows = [];
        $centuriesCipherDatasets = collect([]);
        $centuryStats = collect([]);
        $centuryTitles = collect([]);
        $solutions = Solution::all();

        $actualCentury = (int) ceil(now()->year / 100);

        foreach ($solutions as $solution) {

            $centuries[$solution->name] = collect([]);

            for ($i = 15; $i <= $actualCentury; $i++) {

                $year = $i * 100;
                $from = Carbon::createFromFormat("Y", $year)->startOfCentury()->toDateString();
                $to = Carbon::createFromFormat("Y", $year)->endOfCentury()->toDateString();

                $cipherKeyByCentury = Cryptogram::whereBetween('date', [$from, $to])->approved()->where('solution_id', $solution->id)->count();
                $centuriesCipherDatasets->push($cipherKeyByCentury);

                $centuries[$solution->name]->push($cipherKeyByCentury);
                $centuriesRows[$i . ". century"][$solution->name] = $cipherKeyByCentury;
            }


            $cipherKeyByCentury = Cryptogram::whereNull('date')->approved()->where('solution_id', $solution->id)->count();
            $centuriesCipherDatasets->push($cipherKeyByCentury);

            $centuries[$solution->name]->push($cipherKeyByCentury);
            $centuriesRows['Not recognized'][$solution->name] = $cipherKeyByCentury;

            $centuries[$solution->name] = $centuries[$solution->name]->toArray();

            $color = self::COLORS[array_rand(self::COLORS)];
            $centuryStats->push([
                'label' => $solution->name,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'data' => $centuriesCipherDatasets->toArray(),
                'fill' => false,
                'barThickness' => 8,
            ]);

            $centuriesCipherDatasets = collect([]);
        }

        for ($i = 15; $i <= $actualCentury; $i++) {
            $centuryTitles->push(['title' => $i . ". century"]);
        }
        $centuryTitles->push(['title' => "Not recognized"]);

        return [
            'centuries_title' => $centuryTitles->pluck('title')->toArray(),
            'centuries' => $centuries,
            'datasets' => $centuryStats->toArray(),
            'centuriesRows' => $centuriesRows
        ];
    }

    /**
     * Get cipher symbols stats by century
     *
     * @return array
     */
    private function cryptoByContinent()
    {
        $locations = collect([]);
        $locationsRows = [];
        $locationsCipherDatasets = collect([]);
        $locationStats = collect([]);
        $locationTitles = collect([]);
        $solutions = Solution::all();
        $continents = collect(Location::CONTINENTS);

        foreach ($solutions as $solution) {

            $locations[$solution->name] = collect([]);

            foreach ($continents as $continent) {
                $cipherKeyBylocation = Cryptogram::whereHas('location', function (Builder $query) use ($continent) {
                    $query->where('continent', $continent['name']);
                })->approved()->where('solution_id', $solution->id)->count();
                $locationsCipherDatasets->push($cipherKeyBylocation);

                $locations[$solution->name]->push($cipherKeyBylocation);
                $locationsRows[$continent['name']][$solution->name] = $cipherKeyBylocation;
            }

            $locations[$solution->name] = $locations[$solution->name]->toArray();

            $color = self::COLORS[array_rand(self::COLORS)];
            $locationStats->push([
                'label' => $solution->name,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'data' => $locationsCipherDatasets->toArray(),
                'fill' => false,
                'barThickness' => 8,
            ]);

            $locationsCipherDatasets = collect([]);
        }

        return [
            'locations_title' => $continents->map(function ($item) {
                return [
                    'title' => $item['name']
                ];
            })->pluck('title')->toArray(),
            'locations' => $locations,
            'datasets' => $locationStats->toArray(),
            'locationsRows' => $locationsRows
        ];
    }


    /**
     * Get cipher symbols stats by century
     *
     * @return array
     */
    private function cryptoSymbolByCentury()
    {
        $centuries = collect([]);
        $centuriesRows = [];
        $centuriesCryptoDatasets = collect([]);
        $centuryStats = collect([]);
        $centuryTitles = collect([]);
        $usedChars = ['L' => '#fa7315', 'S' => '#29B6F6', 'N' => '#2E7D32', 'D' => '#FDD835', 'M' => '#E91E63', 'G' => '#6A1B9A'];

        $actualCentury = (int) ceil(now()->year / 100);

        foreach ($usedChars as $char => $color) {

            $centuries[$char] = collect([]);

            for ($i = 15; $i <= $actualCentury; $i++) {

                $year = $i * 100;
                $from = Carbon::createFromFormat("Y", $year)->startOfCentury()->toDateString();
                $to = Carbon::createFromFormat("Y", $year)->endOfCentury()->toDateString();

                $cryptogramByCentury = Cryptogram::whereBetween('date', [$from, $to])->approved()->where('used_chars', 'like', "%$char%")->count();
                $centuriesCryptoDatasets->push($cryptogramByCentury);

                $centuries[$char]->push($cryptogramByCentury);
                $centuriesRows[$i . ". century"][$char] = $cryptogramByCentury;
            }


            $cryptogramByCentury = Cryptogram::whereNull('date')->approved()->where('used_chars', 'like', "%$char%")->count();
            $centuriesCryptoDatasets->push($cryptogramByCentury);

            $centuries[$char]->push($cryptogramByCentury);
            $centuriesRows['Not recognized'][$char] = $cryptogramByCentury;

            $centuries[$char] = $centuries[$char]->toArray();

            $centuryStats->push([
                'label' => $char,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'data' => $centuriesCryptoDatasets->toArray(),
                'fill' => false,
                'barThickness' => 8,
            ]);

            $centuriesCryptoDatasets = collect([]);
        }

        for ($i = 15; $i <= $actualCentury; $i++) {
            $centuryTitles->push(['title' => $i . ". century"]);
        }
        $centuryTitles->push(['title' => "Not recognized"]);

        return [
            'centuries_title' => $centuryTitles->pluck('title')->toArray(),
            'centuries' => $centuries,
            'datasets' => $centuryStats->toArray(),
            'centuriesRows' => $centuriesRows
        ];
    }
}
