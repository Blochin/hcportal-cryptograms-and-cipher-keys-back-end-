<?php

namespace App\Http\Resources\CipherKeyPerson;

use App\Http\Resources\Person\PersonResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CipherKeyPersonResource extends JsonResource
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
            'person' => new PersonResource($this->whenLoaded('person')),
            'is_main_user' => $this->is_main_user
        ];
    }
}
