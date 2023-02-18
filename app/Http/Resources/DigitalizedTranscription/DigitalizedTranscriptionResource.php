<?php

namespace App\Http\Resources\DigitalizedTranscription;

use App\Http\Resources\Data\DataResource;
use App\Http\Resources\EncryptionPair\EncryptionPairResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DigitalizedTranscriptionResource extends JsonResource
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
            'digitalized_version' => $this->digitalized_version,
            'note' => $this->note,
            'digitalized_date' => $this->digitalized_date,
            'created_by' => new UserResource($this->whenLoaded('submitter')),
            'encryption_pairs' => EncryptionPairResource::collection($this->whenLoaded('encryptionPairs'))
        ];
    }
}
