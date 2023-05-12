<?php

namespace Database\Seeders;

use App\Models\Solution;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SolutionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('solutions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $solutions = [
            ['name' => 'Not solved'],
            ['name' => 'Solved'],
            ['name' => 'Partially solved'],
        ];

        foreach ($solutions as $solution) {
            Solution::create($solution);
        }
    }
}
