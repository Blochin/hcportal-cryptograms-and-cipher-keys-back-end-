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
use Illuminate\Http\Request;

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

    public function execWorker(Request $request): \Illuminate\Http\JsonResponse
    {
        //use pwd command to find root directory
        chdir(base_path());
        exec("php artisan queue:work > /dev/null 2>&1 &");
        return response()->json(['success' => true, 'message' => 'Worker successfully prepared to start.']);
    }

    public function killWorker(): \Illuminate\Http\JsonResponse
    {
        exec("pkill -f 'artisan queue:work'");
        return response()->json(['success' => true, 'message' => 'Worker process killed successfully.']);
    }
}
