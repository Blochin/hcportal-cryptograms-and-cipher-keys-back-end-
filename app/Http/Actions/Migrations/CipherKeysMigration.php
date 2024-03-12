<?php

namespace App\Http\Actions\Migrations;

use App\Jobs\ProcessCipherKeyMigration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CipherKeysMigration extends Migration
{

    protected function getData()
    {
        $records = DB::table('nomenclatorkeys')->get();
        $archives = $this->archives();
        $locations = DB::table('places')->get();
        $states = DB::table('nomenclatorkeystate')->get();
        $images = DB::table('nomenclatorimages')->get();
        $users = $this->users();

        return compact('records', 'locations', 'states', 'archives', 'images', 'users');
    }

    protected function processRecord($record)
    {
        $sanitized = [];

        $locations = $this->data['locations'];
        $states = $this->data['states'];
        $archives = $this->data['archives'];
        $images = $this->data['images'];
        $users = $this->data['users'];

        $sanitized['state'] = $states->filter(function ($state) use ($record) {
            return $state->id == $record->stateId;
        })->map(function ($state) {
            if (!$state) {
                return null;
            }
            return $state->state;
        })->first();
        if ($sanitized['state'] != 'approved') {
            return null;
        }
        $sanitized['old_id'] = $record->id;
        $sanitized['name'] = $record->signature;
        $sanitized['used_to'] = $record->usedTo;
        $sanitized['used_from'] = $record->usedFrom;
        $sanitized['used_around'] = $record->usedAround;
        $sanitized['used_chars'] = $record->usedChars;
        $sanitized['complete_structure'] = $record->completeStructure;
        $sanitized['language'] = $record->language;
        $sanitized['cipher_type'] = $record->cipherType;
        $sanitized['key_type'] = $this->resolveKeyType($record->keyType);
        $sanitized = array_merge($sanitized, $archives->filter(function ($archive) use ($record) {
            return $archive['folder'] === $record->folder;
        })->map(function ($archive) {
            return [
                'folder' => $archive['folder'],
                'archive' => $archive['archive'],
                'fond' => $archive['fond'],
            ];
        })->first());
        $sanitized['continent'] = $locations->filter(function ($location) use ($record) {
            return $location->id == $record->placeOfCreation;
        })->map(function ($location) {
            if (!$location) {
                return null;
            }
            return $location->name;
        })->first();
        $sanitized['users'] = json_encode($users->filter(function ($user) use ($record) {
            return $user['cipher_key_id'] == $record->id;
        })->map(function ($user) use ($record) {
            return [
                'is_main_user' => $user['is_main_user'],
                'name' => $user['name'],
            ];
        })->values()->all());
        $sanitized['images'] = json_encode($images->filter(function ($image) use ($record) {
            return $image->nomenclatorKeyId == $record->id;
        })->map(function ($image) {
            return [
                'has_instructions' => $image->hasInstructions,
                'structure' => $image->structure,
                'image_link' => str_replace(' ', '%20', $image->url),
            ];
        }));
        return $sanitized;
    }

    public function handle()
    {

        $allSanitized = self::processDatabase();

        DB::setDefaultConnection('mysql');
        DB::purge();
        DB::reconnect();

        foreach ($allSanitized as $sanitized) {
            dispatch(new ProcessCipherKeyMigration($sanitized));
        }
    }

    private function archives()
    {
        $folders = DB::table('folders')->get();
        $fonds = DB::table('fonds')->get();
        $archives = DB::table('archives')->get();

        return $folders->map(function ($folder) use ($fonds, $archives) {
            $fond = $fonds->firstWhere('name', $folder->fond);
            $archive = $archives->firstWhere('name', $fond->archive);

            return [
                'archive' => $archive->name,
                'folder' => $folder->name,
                'fond' => $fond->name,
            ];
        });
    }

    private function users()
    {
        $users = DB::table('nomenclatorkeyusers')->get();
        $userNames = DB::table('keyusers')->get();

        return $users->map(function ($user) use ($userNames) {
            $userName = $userNames->firstWhere('id', $user->userId);
            return [
                'cipher_key_id' => $user->nomenclatorKeyId,
                'name' => $userName->name,
                'is_main_user' => $user->isMainUser,
            ];
        });
    }

    private function resolveKeyType($keyType)
    {
        if ($keyType == "e") {
            return 1;
        } else if ($keyType == "d") {
            return 2;
        } else {
            return 3;
        }
    }

    static function prepareDatabase()
    {
        DB::setDefaultConnection('mysql_3');
    }
}
