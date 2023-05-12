<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $mainCategories = [
            ['name' => 'Unknown'],
            ['name' => 'Substitution'],
            ['name' => 'Transposition'],
            ['name' => 'Composed'],
            ['name' => 'Nomenclator'],
            ['name' => 'Code/Codebook'],
            ['name' => 'Steganography'],
            ['name' => 'Cipher machine'],
            ['name' => 'Other'],
        ];

        foreach ($mainCategories as $mainCategory) {
            Category::create($mainCategory);
        }

        $parent  = Category::where('name', 'Substitution')->first();
        $substitutionSubCategories = [
            ['name' => 'Monoalphabetic', 'parent_id' => $parent->id],
            ['name' => 'Homophonic', 'parent_id' => $parent->id],
            ['name' => 'Polyalphabetic', 'parent_id' => $parent->id],
            ['name' => 'Polygraphic', 'parent_id' => $parent->id],
            ['name' => 'Other', 'parent_id' => $parent->id],
        ];

        foreach ($substitutionSubCategories as $subSub) {
            Category::create($subSub);
        }

        $parent  = Category::where('name', 'Cipher machine')->first();
        $machineSubCategories = [
            ['name' => 'Rotor', 'parent_id' => $parent->id],
            ['name' => 'Pin-and-lug', 'parent_id' => $parent->id],
            ['name' => 'Other', 'parent_id' => $parent->id],
        ];

        foreach ($machineSubCategories as $machineSub) {
            Category::create($machineSub);
        }

    }
}
