<?php

namespace Database\Seeders;

use App\Models\CipherType;
use App\Models\KeyType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CipherAndKeyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('key_types')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $keyTypes = [
            ['name' => 'e'],
            ['name' => 'd'],
            ['name' => 'ed'],
        ];

        foreach ($keyTypes as $type) {
            KeyType::create($type);
        }
    }
}
