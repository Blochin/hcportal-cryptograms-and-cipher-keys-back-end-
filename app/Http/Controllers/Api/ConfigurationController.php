<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConfigurationResource;
use App\Models\Archive;
use App\Models\Category;
use App\Models\KeyType;
use App\Models\Language;
use App\Models\Location;
use App\Models\Person;
use App\Models\Solution;
use App\Models\Tag;

class ConfigurationController extends Controller
{
    public function index()
    {
        $archives = Archive::with(['fonds', 'fonds.folders'])->get();
        $categories = Category::with(['children'])->where('parent_id', null)->get();
        $languages = Language::all();
        $locations = Location::all();
        $solutions = Solution::all();
        $persons = Person::all();
        $continents = collect(Location::CONTINENTS);
        $tags = Tag::all();
        $keyTypes = KeyType::all();

        $data = new ConfigurationResource([
            'archives' => $archives,
            'categories' => $categories,
            'languages' => $languages,
            'locations' => $locations,
            'continents' => $continents,
            'solutions' => $solutions,
            'persons' => $persons,
            'tags' => $tags,
            'key_types' => $keyTypes
        ]);

        return response()->json($data);
    }
}
