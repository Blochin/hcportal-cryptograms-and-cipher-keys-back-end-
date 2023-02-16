<?php

namespace Database\Seeders;

use App\Models\CipherType;
use App\Models\KeyType;
use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('locations')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $continents = [
            ['continent' => 'North America'],
            ['continent' => 'South America'],
            ['continent' => 'Europe'],
            ['continent' => 'Asia'],
            ['continent' => 'Oceania'],
            ['continent' => 'Africa'],
            ['continent' => 'Antartica'],
            ['continent' => 'Unknown']
        ];

        foreach ($continents as $continent) {
            Location::create($continent);
        }
    }
}
