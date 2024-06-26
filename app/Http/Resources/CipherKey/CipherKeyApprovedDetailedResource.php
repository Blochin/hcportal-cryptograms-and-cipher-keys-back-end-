<?php

namespace App\Http\Resources\CipherKey;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\CipherKeyImage\CipherKeyImageResource;
use App\Http\Resources\CipherKeyPerson\CipherKeyPersonResource;
use App\Http\Resources\CipherType\CipherTypeResource;
use App\Http\Resources\DigitalizedTranscription\DigitalizedTranscriptionResource;
use App\Http\Resources\KeyType\KeyTypeResource;
use App\Http\Resources\Language\LanguageResource;
use App\Http\Resources\Tag\TagResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CipherKeyApprovedDetailedResource extends JsonResource
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
            'availability_type' => $this->availability_type,
            'availability' => $this->availability,
            'description' => $this->description,
            'complete_structure' => $this->complete_structure,
            'used_chars' => $this->used_chars,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'key_type' => new KeyTypeResource($this->whenLoaded('keyType')),
            'used_from' => $this->used_from,
            'used_to' => $this->used_to,
            'used_around' => $this->used_around,
            'folder' => $this->whenLoaded('folder'),
            'location' => $this->whenLoaded('location'),
            'language' => new LanguageResource($this->whenLoaded('language')),
            'state' => $this->state,
            'note' => $this->note,
            'created_by' => new UserResource($this->whenLoaded('submitter')),
            'images' => CipherKeyImageResource::collection($this->whenLoaded('images')),
            'users' => CipherKeyPersonResource::collection($this->whenLoaded('users')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'digitalized_transcriptions' => DigitalizedTranscriptionResource::collection($this->whenLoaded('digitalizedTranscriptions')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }
}
