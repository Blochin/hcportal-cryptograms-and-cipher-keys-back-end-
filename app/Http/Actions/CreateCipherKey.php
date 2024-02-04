<?php

namespace App\Http\Actions;

use App\Models\Category;
use App\Models\CipherKey;
use App\Models\KeyType;
use App\Models\Language;
use App\Models\Location;
use App\Traits\CipherKey\CipherKeySyncable;
use Illuminate\Support\Facades\Log;

class CreateCipherKey
{
    use CipherKeySyncable;

    public function handle(array $sanitized)
    {
        try {
            $sanitized = $this->getSanitized($sanitized);
            $cipherKey = CipherKey::create($sanitized);

            //Store CipherKey Images
            $this->syncCipherKeyImagesApi($cipherKey, $sanitized);

            //Store CipherKey Users
            $this->syncCipherKeyUsers($cipherKey, $sanitized, 'create', 'api');

            //Store archives,fonds,folders
            $this->syncArchive($cipherKey, $sanitized, true);

            //Sync tags
            $this->syncTags($cipherKey, $sanitized, 'api', 'cipher_key');

            $this->syncCryptogramsApi($cipherKey, $sanitized);

            //Load relationships
            $cipherKey->load([
                'images',
                'users',
                'cryptograms',
                'users.person',
                'submitter',
                'category',
                'keyType',
                'group',
                'folder',
                'folder.fond',
                'folder.fond.archive',
                'digitalizedTranscriptions',
                'digitalizedTranscriptions.encryptionPairs',
                'language',
                'location',
                'tags'
            ]);

            return $cipherKey;
        } catch (\Exception $e) {
            Log::debug('Cipher key failed'. $sanitized['old_id']);
        }
        return null;
    }

    private function getSanitized($sanitized)
    {
        $sanitized['images'] = $sanitized['images'] ? json_decode($sanitized['images']) : null;
        $sanitized['users'] = $sanitized['users'] ? json_decode($sanitized['users']) : null;

        $sanitized['created_by'] = 1;

        $undefinedLanguage = Language::where('name', 'Unknown')->first()->id;
        $language = Language::where('name', $sanitized['language'])->first();
        $sanitized['language_id'] = $language ? $language->id : $undefinedLanguage;

        if (isset($sanitized['location_name']) && isset($sanitized['continent'])) {
            $location = Location::firstOrCreate([
                'name' => $sanitized['location_name'],
                'continent' => $sanitized['continent']
            ]);
        } elseif (isset($sanitized['continent'])) {
            $location = Location::firstOrCreate([
                'continent' => $sanitized['continent']
            ]);
        } else {
            $location = Location::firstOrCreate([
                'continent' => 'Unknown'
            ]);
        }

        $otherCategory = Category::where('name', 'Other')->first()->id;
        $category = Category::where('name', $sanitized['cipher_type'])->first();
        $sanitized['category_id'] = $category ? $category->id : $otherCategory;

        if (isset($sanitized['subcategory_id']) && $sanitized['subcategory_id']) {
            $sanitized['category_id'] = $sanitized['subcategory_id'] ? $sanitized['subcategory_id'] : null;
        }

        $sanitized['location_id'] = $location->id;

        return $sanitized;
    }
}
