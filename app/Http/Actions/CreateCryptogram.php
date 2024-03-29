<?php

namespace App\Http\Actions;

use App\Models\Category;
use App\Models\CipherKey;
use App\Models\Cryptogram;
use App\Models\Language;
use App\Models\Location;
use App\Models\Person;
use App\Models\Solution;
use App\Traits\CipherKey\CipherKeySyncable;
use App\Traits\Cryptogram\CryptogramSyncable;

class CreateCryptogram
{
    use CryptogramSyncable;
    use CipherKeySyncable;

    public function handle($sanitized)
    {
        $sanitized = $this->getSanitized($sanitized);
        $cryptogram = Cryptogram::create($sanitized);

        //Sync thumbnail
        if (isset($sanitized['thumbnail']) && $sanitized['thumbnail']) {
            $cryptogram
                ->addMedia($sanitized['thumbnail'])
                ->toMediaCollection('picture');
        } elseif (isset($sanitized['thumbnail_link']) && $sanitized['thumbnail_link']) {
            $cryptogram
                ->addMediaFromUrl($sanitized['thumbnail_link'])
                ->toMediaCollection('picture');
        } elseif (isset($sanitized['thumbnail_base64']) && $sanitized['thumbnail_base64']) {
            $cryptogram
                ->addMediaFromBase64($sanitized['thumbnail_base64'])
                ->toMediaCollection('picture');
        }

        //Sync datagroups
        $this->syncDatagroupsApi($cryptogram, $sanitized, 'api');

        //Sync tags
        $this->syncTagsCryptogram($cryptogram, $sanitized, 'api');

        //Store archives,fonds,folders
        $this->syncArchive($cryptogram, $sanitized, true);

        $this->syncCiperKeysApi($cryptogram, $sanitized);

        //Load relationships
        $cryptogram->load([
            'sender',
            'cipherKeys',
            'language',
            'location',
            'recipient',
            'solution',
            'category',
            'category.children',
            'folder',
            'folder.fond',
            'folder.fond.archive',
            'tags',
            'groups',
            'groups.data',
            'submitter',
        ]);
    }

    private function getSanitized($sanitized): array
    {
        $sanitized['thumbnail_url'] = 'temporary value';

        $undefinedLanguage = Language::where('name', 'Unknown')->first()->id;
        $language = Language::where('name', $sanitized['language'])->first();
        $sanitized['language_id'] = $language ? $language->id : $undefinedLanguage;

        $sanitized['sender_id'] = $sanitized['sender'] ? Person::firstOrCreate(['name' => $sanitized['sender']])->id : Person::firstOrCreate(['name' => 'Unknown'])->id;
        $sanitized['recipient_id'] = $sanitized['recipient'] ? Person::firstOrCreate(['name' => $sanitized['recipient']])->id : Person::firstOrCreate(['name' => 'Unknown'])->id;
        $sanitized['groups'] = isset($sanitized['groups']) && $sanitized['groups'] ? json_decode($sanitized['groups']) : null;

        $sanitized['state'] = $sanitized['state'] ?? CipherKey::STATUS_AWAITING;
        $sanitized['availability'] = isset($sanitized['availability']) && $sanitized['availability'] ? $sanitized['availability'] : null;

        if (isset($sanitized['continent'])) {
            $location = Location::firstOrCreate([
                'continent' => $sanitized['continent']
            ]);
        } else {
            $location = Location::firstOrCreate([
                'continent' => 'Unknown'
            ]);
        }

        $solution = Solution::where('name', $sanitized['solution'])->first();

        $sanitized['solution_id'] = $solution ? $solution->id : 1;

        $unknownCategory = Category::where('name', 'Unknown')->first();
        $category = Category::where('name', $sanitized['category'])->first();
        $sanitized['category_id'] = $category ? $category->id : $unknownCategory->id;

        if (isset($sanitized['subcategory_id']) && $sanitized['subcategory_id']) {
            $sanitized['category_id'] = $sanitized['subcategory_id'] ?: null;
        }

        $sanitized['location_id'] = $location->id;

        return $sanitized;
    }
}
