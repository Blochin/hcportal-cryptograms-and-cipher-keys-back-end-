<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('languages')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        $languages = [
            ['name' => 'Unknown'],
            ['name' => 'Czech'],
            ['name' => 'Chinese'],
            ['name' => 'Danish'],
            ['name' => 'Dutch'],
            ['name' => 'English'],
            ['name' => 'French'],
            ['name' => 'German'],
            ['name' => 'Greek'],
            ['name' => 'Hungarian'],
            ['name' => 'Italian'],
            ['name' => 'Japanese'],
            ['name' => 'Korean'],
            ['name' => 'Latin'],
            ['name' => 'Polish'],
            ['name' => 'Portuguese'],
            ['name' => 'Romanian'],
            ['name' => 'Russian'],
            ['name' => 'Slovak'],
            ['name' => 'Spanish'],
            ['name' => 'Swedish'],
            ['name' => 'Other'],
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
    }
}
