<?php

namespace App\Traits\Cryptogram;

use App\Models\Cryptogram;
use App\Models\Data;
use App\Models\Datagroup;
use App\Models\Tag;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Cryptogram Syncable
|--------------------------------------------------------------------------
|
| This trait will be used for sync relations to Cryptogram
|
*/

trait CryptogramSyncable
{
    /**
     * Sync datagroups
     *
     * @param Cryptogram $cryptogram
     * @param array $sanitized
     * @return void
     */
    private function syncDatagroups(Cryptogram $cryptogram, $sanitized, $origin = 'web')
    {

        if (!isset($sanitized['groups'])) return;

        // 1. Delete groups
        $cryptogram->groups()->delete();

        // 2. Sync new groups
        foreach ($sanitized['groups'] as $keyGroup => $group) {
            $newGroup = Datagroup::create(['description' => $group->description, 'cryptogram_id' => $cryptogram->id]);
            $data = collect($group->data)->toArray();
            foreach ($data as $keyData => $item) {

                $item = collect($item)->toArray();
                if ($origin == 'web') {
                    $type = collect($item['type'])->toArray();
                } else {
                    $type['id'] = $item['type'];
                }

                $newData = Data::create([
                    'blob' => $item['blob'],
                    'description' => $item['title'],
                    'filetype' => $type['id'],
                    'datagroup_id' => $newGroup->id,
                    'dl_protection' => 0
                ]);

                //Sync image to data in datagroup
                if (isset($sanitized['images'][$keyGroup][$keyData]) && $sanitized['images'][$keyGroup][$keyData] !== 'undefined') {

                    $newData
                        ->addMedia($sanitized['images'][$keyGroup][$keyData])
                        ->toMediaCollection('image');

                    $newData->update(['blob' => $newData->getFirstMediaPath('image')]);
                } elseif (isset($item['image']) && $item['image']) {
                    $newData
                        ->addMediaFromUrl($item['image'])
                        ->toMediaCollection('image');
                }
            }
        }

        // // 3. Sync predefined groups
        // $predefinedGroups = collect($sanitized['predefined_groups'])->pluck('id')->toArray();
        // $cryptogram->datagroups()->sync($predefinedGroups);
    }


    private function syncDatagroupsApi(Cryptogram $cryptogram, $sanitized)
    {

        if (!isset($sanitized['groups'])) return;

        // 1. Delete groups
        $cryptogram->groups()->delete();

        // 2. Sync new groups
        foreach ($sanitized['groups'] as $keyGroup => $group) {
            $newGroup = Datagroup::create(['description' => $group->description, 'cryptogram_id' => $cryptogram->id]);
            $data = collect($group->data)->toArray();
            foreach ($data as $keyData => $item) {
                $item = collect($item)->toArray();

                $dataBlobb = isset($item['link']) ? $item['link'] : (isset($item['text']) ? $item['text'] : null);
                $newData = Data::create([
                    'blob' => $item['type'] == 'image' ? 'image' : $dataBlobb,
                    'description' => $item['title'],
                    'filetype' => $item['type'],
                    'datagroup_id' => $newGroup->id,
                    'dl_protection' => 0
                ]);

                if (isset($item['image_base64']) && $item['type'] === 'image') {
                    $newData
                        ->addMediaFromBase64($item['image_base64'])
                        ->toMediaCollection('image');
                    $newData->update(['blob' => $newData->getFirstMediaPath('image')]);
                } else if (isset($item['image_link']) && $item['type'] === 'image') {
                    $newData
                        ->addMediaFromUrl($item['image_link'])
                        ->toMediaCollection('image');
                    $newData->update(['blob' => $newData->getFirstMediaPath('image')]);
                }
            }
        }
    }


    /**
     * Sync tags
     *
     * @param Cryptogram $key
     * @param array $sanitized
     * @return void
     */
    public function syncTagsCryptogram(Cryptogram $cryptogram, $sanitized, $origin = 'web', $type = 'cryptogram')
    {
        if (!isset($sanitized['tags'])) return;

        $tags = collect($sanitized['tags'])->pluck('id');

        if ($origin == 'api') {
            $tags = collect([]);
            foreach ($sanitized['tags'] as $tag) {
                $tag = Tag::where('type', $type)->firstOrCreate(['name' => $tag], ['type' => $type]);
                $tags->push($tag->id);
            }
        }


        $cryptogram->tags()->sync($tags->toArray());
    }

    /**
     * Sync cipher keys
     *
     * @param Cryotogram $cryptogram
     * @param array $sanitized
     * @return void
     */
    public function syncCipherKeys($cryptogram, $sanitized)
    {
        $keys = collect($sanitized['cipher_keys'])->pluck('id')->toArray();
        $cryptogram->cipherKeys()->sync($keys);
    }

    /**
     * @throws \Exception
     */
    public function syncCiperKeysApi($cryptogram, $sanitized)
    {
        if (empty($sanitized['cipher_key_id'])) {
            $cryptogram->cipherKeys()->detach();
            return;
        }
        try {
            $keys = collect($sanitized['cipher_key_id'])->toArray();
            $cryptogram->cipherKeys()->sync($keys);
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}
