<?php

namespace App\Http\Resources\EncryptionPair;

use App\Http\Resources\Data\DataResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EncryptionPairResource extends JsonResource
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
            'plain_text_unit' => $this->plain_text_unit,
            'cipher_text_unit' => $this->cipher_text_unit,
        ];
    }
}
