<?php

namespace App\Http\Resources\Datagroup;

use App\Http\Resources\Data\DataResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DatagroupResource extends JsonResource
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
            'description' => $this->description,
            'data' => DataResource::collection($this->whenLoaded('data'))
        ];
    }
}
