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

        DB::table('cipher_types')->truncate();
        DB::table('key_types')->truncate();

        $cipherTypes = [
            ['name' => 'Undefined'],
            ['name' => 'Nomenclator'],
            ['name' => 'Code'],
            ['name' => 'Simple substitution']
        ];

        $keyTypes = [
            ['name' => 'e'],
            ['name' => 'ed'],
        ];

        foreach ($cipherTypes as $type) {
            CipherType::create($type);
        }

        foreach ($keyTypes as $type) {
            KeyType::create($type);
        }
    }
}
