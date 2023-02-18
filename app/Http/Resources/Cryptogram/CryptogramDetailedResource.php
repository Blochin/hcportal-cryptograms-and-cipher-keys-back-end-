<?php

namespace App\Http\Resources\Cryptogram;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\CipherKeyImage\CipherKeyImageResource;
use App\Http\Resources\CipherKeyPerson\CipherKeyPersonResource;
use App\Http\Resources\Datagroup\DatagroupResource;
use App\Http\Resources\Language\LanguageResource;
use App\Http\Resources\Location\LocationResource;
use App\Http\Resources\Person\PersonResource;
use App\Http\Resources\Solution\SolutionResource;
use App\Http\Resources\Tag\TagResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CryptogramDetailedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'availability' => $this->availability,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'day' => $this->day,
            'month' => $this->month,
            'year' => $this->year,
            'before_crist' => $this->flag,
            'thumb' => $this->getFirstMediaUrl('picture', 'large'),
            'language' => new LanguageResource($this->whenLoaded('language')),
            'location' => new LocationResource($this->whenLoaded('location')),
            'recipient' => new PersonResource($this->whenLoaded('recipient')),
            'sender' => new PersonResource($this->whenLoaded('sender')),
            'solution' => new SolutionResource($this->whenLoaded('solution')),
            'state' => $this->state,
            'created_by' => new UserResource($this->whenLoaded('submitter')),
            'note' => $this->note,
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'datagroups' => DatagroupResource::collection($this->whenLoaded('groups')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }
}
