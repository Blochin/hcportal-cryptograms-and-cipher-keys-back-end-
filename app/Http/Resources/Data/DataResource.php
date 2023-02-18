<?php

namespace App\Http\Resources\Data;

use Illuminate\Http\Resources\Json\JsonResource;

class DataResource extends JsonResource
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
            'title' => $this->title,
            'type' => $this->filetype,
            'link' => $this->when($this->filetype == 'link', $this->blobb),
            'text' =>  $this->when($this->filetype == 'text', $this->blobb),
            'image' => $this->when($this->filetype == 'image', [
                'thumb' => $this->getFirstMediaUrl('image', 'thumb'),
                'big' => $this->getFirstMediaUrl('image', 'big'),
                'large' => $this->getFirstMediaUrl('image', 'large'),
                'original' => $this->getFirstMediaUrl('image'),
            ])
        ];
    }
}
