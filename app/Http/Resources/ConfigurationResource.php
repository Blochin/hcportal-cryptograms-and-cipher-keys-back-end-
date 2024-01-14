<?php

namespace App\Http\Resources;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Language\LanguageResource;
use App\Http\Resources\Location\LocationResource;
use App\Http\Resources\Person\PersonResource;
use App\Http\Resources\Solution\SolutionResource;
use App\Http\Resources\Tag\TagResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigurationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'archives' => $this->resource['archives'],
            'categories' => CategoryResource::collection($this->resource['categories']),
            'languages' => LanguageResource::collection($this->resource['languages']),
            'locations' => LocationResource::collection($this->resource['locations']),
            'continents' => $this->resource['continents'],
            'solutions' => SolutionResource::collection($this->resource['solutions']),
            'persons' => PersonResource::collection($this->resource['persons']),
            'tags' => TagResource::collection($this->resource['tags']),
            'key_types' => $this->resource['key_types'],
        ];
    }
}
