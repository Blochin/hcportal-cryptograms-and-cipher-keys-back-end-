<?php

namespace App\Http\Resources\CipherKeyImage;

use App\Http\Resources\Person\PersonResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CipherKeyImageResource extends JsonResource
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
            'url' => [
                'thumb' => $this->getFirstMediaUrl('picture', 'thumb'),
                'big' => $this->getFirstMediaUrl('picture', 'big'),
                'large' => $this->getFirstMediaUrl('picture', 'large'),
                'original' => $this->getFirstMediaUrl('picture'),
            ],
            'structure' => $this->structure,
            'has_instructions' => $this->has_instructions,
        ];
    }
}
