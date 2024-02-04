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
use App\Models\Category;
use App\Models\CipherKey;
use App\Models\Cryptogram;
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
            'cipher_key_id' => $this->whenLoaded('cipherKeys', function () {
                return $this->resolveCipherKeyPermissions();
            }),
            'name' => $this->name,
            'description' => $this->description,
            'availability_type' => $this->availability_type,
            'availability' => $this->availability,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'sub_category' => new CategoryResource($this->category->parent),
            'date' => $this->date,
            'date_around' => $this->date_around,
            'thumb' => $this->getFirstMediaUrl('picture', 'large'),
            'used_chars' => $this->used_chars,
            'language' => new LanguageResource($this->whenLoaded('language')),
            'location' => new LocationResource($this->whenLoaded('location')),
            'recipient' => new PersonResource($this->whenLoaded('recipient')),
            'sender' => new PersonResource($this->whenLoaded('sender')),
            'solution' => new SolutionResource($this->whenLoaded('solution')),
            'folder' => $this->whenLoaded('folder'),
            'state' => $this->state,
            'created_by' => new UserResource($this->whenLoaded('submitter')),
            'note' => $this->note,
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'datagroups' => DatagroupResource::collection($this->whenLoaded('groups')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }

    private function resolveCipherKeyPermissions() {
        if(empty($this->cipherKeys)){
            return [];
        }
        $cipherKeysId = null;
        /** @var CipherKey $cipherKey */
        foreach ($this->cipherKeys as $cipherKey) {
            if($cipherKey->approved()){
                $cipherKeysId[] = $cipherKey->id;
            } else if($cipherKey->created_by = auth()->user()->id){
                $cipherKeysId[] = $cipherKey->id;
            }
        }
        return $cipherKeysId;
    }
}
