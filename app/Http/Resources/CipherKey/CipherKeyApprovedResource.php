<?php

namespace App\Http\Resources\CipherKey;

use Illuminate\Http\Resources\Json\JsonResource;

class CipherKeyApprovedResource extends JsonResource
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
            'signature' => $this->signature,
        ];
    }
}
