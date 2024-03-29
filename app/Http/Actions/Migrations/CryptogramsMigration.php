<?php

namespace App\Http\Actions\Migrations;

use App\Jobs\ProcessCryptogramMigration;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CryptogramsMigration extends Migration
{
    protected function getData()
    {
        $records = DB::table('cipher')->get();
        $locations = DB::table('location')->get();
        $solutions = DB::table('solved')->get();
        $statuses = DB::table('status')->get();
        $availabilities = DB::table('availability')->get();
        $categories = DB::table('category')->get();
        $languages = DB::table('language')->get();
        $users = DB::table('person')->get();
        $tags = $this->tags();
        $groups = $this->groups();

        return compact('records', 'locations', 'solutions', 'statuses', 'availabilities', 'categories', 'languages', 'users', 'tags', 'groups');
    }

    protected function processRecord($record)
    {
        $sanitized = [];

        $statuses = $this->data['statuses'];
        $locations = $this->data['locations'];
        $categories = $this->data['categories'];
        $languages = $this->data['languages'];
        $users = $this->data['users'];
        $solutions = $this->data['solutions'];
        $tags = $this->data['tags'];
        $groups = $this->data['groups'];
        $availabilities = $this->data['availabilities'];

        $sanitized['state'] = $statuses->filter(function ($status) use ($record) {
            return $status->id == $record->statusId;
        })->map(function ($status) use ($record) {
            return $status->status;
        })->first();
        if ($sanitized['state'] !== 'approved') {
            return null;
        }
        $sanitized['created_by'] = $this->user->id;
        $sanitized['old_id'] = $record->id;
        $sanitized['name'] = $record->name;
        $sanitized['thumbnail_link'] = str_replace(' ', '%20', $record->imageURL);
        $sanitized['description'] = $record->description;
        $sanitized = array_merge($sanitized, $this->parseDate($record->day, $record->month, $record->year));
        $sanitized['continent'] = $locations->filter(function ($location) use ($record) {
            return $location->id == $record->locationId;
        })->map(function ($location) {
            return $location->name;
        })->first();
        $sanitized['category'] = $categories->filter(function ($category) use ($record) {
            return $category->id == $record->categoryId;
        })->map(function ($category) use ($categories) {
            return $category->name;
        })->first();
        $sanitized['language'] = $languages->filter(function ($language) use ($record) {
            return $language->id == $record->languageId;
        })->map(function ($language) {
            return $language->name;
        })->first();
        $sanitized['sender'] = $users->filter(function ($user) use ($record) {
            return $user->id == $record->senderId;
        })->map(function ($user) {
            return $user->name;
        })->first();
        $sanitized['recipient'] = $users->filter(function ($user) use ($record) {
            return $user->id == $record->recipientId;
        })->map(function ($user) {
            return $user->name;
        })->first();
        $sanitized['solution'] = $solutions->filter(function ($solution) use ($record) {
            return $solution->id == $record->solvedId;
        })->map(function ($solution) {
            return $solution->name;
        })->first();
        $sanitized['tags'] = $tags->filter(function ($tag) use ($record) {
            return $tag['cryptogram_id'] == $record->id;
        })->map(function ($tag) {
            if (!$tag) {
                return null;
            }
            return $tag['name'];
        })->values()->unique()->all();
        $sanitized['groups'] = json_encode($groups->filter(function ($group) use ($record) {
            return $group['cryptogram_id'] == $record->id;
        })->values()->all());

        $archive = $availabilities->filter(function ($availability) use ($record) {
            return $availability->id == $record->availabilityId;
        })->map(function ($availability) {
            return $availability->name;
        })->first();

        $sanitized = array_merge($sanitized, $this->processArchive($archive));

        return $sanitized;
    }
    public function handle()
    {
        $allSanitized = self::processDatabase();
        $allSanitized = array_filter($allSanitized, function ($value) {
            return $value !== null;
        });

        DB::setDefaultConnection('mysql');
        DB::purge();
        DB::reconnect();

        foreach ($allSanitized as $sanitized) {
            dispatch(new ProcessCryptogramMigration($sanitized));
        }
    }

    private function tags()
    {
        $tagsCiphers = DB::table('tagsCipher')->get();
        $tags = DB::table('tags')->get();

        return $tagsCiphers->map(function ($tagCipher) use ($tags) {
            $tag = $tags->firstWhere('id', $tagCipher->tagsId);
            return [
                'name' => $tag->name,
                'cryptogram_id' => $tagCipher->cipherId,
            ];
        });
    }

    private function groups()
    {
        $dataGroups = DB::table('datagroup')->get();
        $data = DB::table('data')->get();

        return $dataGroups->map(function ($dataGroup) use ($data) {
            $groupItems = $data->where('datagroupId', $dataGroup->id)->map(function ($item) {
                if ($item->filetype === 'link') {
                    return [
                        'type' => $item->filetype,
                        'title' => $item->description,
                        'link' => $item->blobb
                    ];
                } else if ($item->filetype == 'text') {
                    return [
                        'type' => $item->filetype,
                        'title' => $item->description,
                        'text' => $item->blobb
                    ];
                } else if ($item->filetype == 'image') {
                    return [
                        'type' => $item->filetype,
                        'title' => $item->description,
                        'image_link' => str_replace(' ', '%20', $item->blobb),
                    ];
                }
                return null;
            })->toArray();

            return [
                'id' => $dataGroup->cipherId,
                'cryptogram_id' => $dataGroup->cipherId,
                'description' => $dataGroup->description,
                'data' => $groupItems,
            ];
        });
    }

    private function parseDate($day, $month, $year)
    {
        if ($day != 0 && $month != 0 && $year != 0) {
            $carbonDate = Carbon::createFromFormat('d.m.Y', $day . "." . $month . "." . $year);
            $databaseFormattedDate = $carbonDate->format('Y-m-d H:i:s');
            return ['date' => $databaseFormattedDate];
        } else if ($day == 0 && $month != 0 && $year != 0) {
            return ['date_around' => $month . "." . $year];
        } else if ($day == 0 && $month == 0 && $year != 8) {
            if($year == 0){
                return ['date_around' => "Unknown"];
            }
            return ['date_around' => $year];
        } else {
            return ['date_around' => "Unknown"];
        }
    }

    function processArchive($name)
    {
        $availability = [];

        if (strpos($name, 'Slovak National Archive. Family archive') !== false) {
            $availability['archive'] = 'Slovak National Archive in Bratislava';
        } elseif (strpos($name, 'Slovak National Archive in Bratislava, f. MZV') !== false) {
            $availability['archive'] = 'Slovak National Archive in Bratislava';
        } elseif (strpos($name, 'hstam') !== false) {
            $availability['archive'] = 'Hessisches Staatsarchiv Marburg';
        } elseif (strpos($name, 'hhstaw') !== false) {
            $availability['archive'] = 'Hessisches Hauptstaatsarchiv Wiesbaden';
        } elseif (strpos($name, 'Státní oblastní archiv Třeboň:') !== false) {
            $availability['archive'] = 'Státní oblastní archiv Třeboň';
        } elseif (strpos($name, 'Gallica, BnF fr.3789, f.3') !== false) {
            $availability['archive'] = 'Bibliothèque nationale de France (BnF)';
        } elseif (strpos($name, 'ABS Praha, f. ZSGS, box BF388a, 27-19/6-099') !== false) {
            $availability['archive'] = 'Archiv bezpečnostních složek';
        } elseif (strpos($name, 'Státní oblastní archiv v Plzni, pracoviště Klášter u Nepomuka, Rodinný archiv Windischgrätzů, inv. nr. 1433, karton nr. 202') !== false) {
            $availability['archive'] = 'Státní oblastní archiv v Plzni, pracoviště Klášter u Nepomuka';
        }


        if (strpos($name, 'Slovak National Archive. Family archive') !== false) {
            $availability['fond'] = 'Family archive Pálffy Červenokamenská línia';
        } elseif (strpos($name, 'Slovak National Archive in Bratislava, f. MZV') !== false) {
            $availability['fond'] = 'MZV';
        } elseif (strpos($name, 'hstam_4_d') !== false) {
            $availability['fond'] = '4 d Kanzlei- und Geheimeratskorrespondenz';
        } elseif (strpos($name, 'hstam_4 d') !== false) {
            $availability['fond'] = '4 d Kanzlei- und Geheimeratskorrespondenz';
        } elseif (strpos($name, 'hstam_300') !== false) {
            $availability['fond'] = '300 - Haus Hessen (ehemals Bestand Hessen-Rumpenheim)';
        } elseif (strpos($name, 'hstam_118_a') !== false) {
            $availability['fond'] = '118 a - Waldeckisches Kabinett';
        } elseif (strpos($name, 'hstam_115_01') !== false) {
            $availability['fond'] = '115/1 - Waldeckische Ältere Kanzleien: Grafenhaus';
        } elseif (strpos($name, 'hstam_9_a') !== false) {
            $availability['fond'] = '9 a - Organisation und Geschäftsgang';
        } elseif (strpos($name, 'hstam_4_h') !== false) {
            $availability['fond'] = '4 h - Kriegssachen';
        } elseif (strpos($name, 'hstam_4_f_schweden') !== false) {
            $availability['fond'] = '4f - Staatenabteilung: Schweden';
        } elseif (strpos($name, 'hstam_4_f_daenemark') !== false) {
            $availability['fond'] = '4f - Staatenabteilung: Dänemark';
        } elseif (strpos($name, 'hstam_5') !== false) {
            $availability['fond'] = '5 - Geheimer Rat';
        } elseif (strpos($name, 'Státní oblastní archiv Třeboň:') !== false) {
            $availability['fond'] = 'Rodinný archiv Buquoyů';
        } elseif (strpos($name, 'Gallica, BnF fr.3789, f.3') !== false) {
            $availability['fond'] = 'Fr. 3789';
        } elseif (strpos($name, 'ABS Praha, f. ZSGS, box BF388a, 27-19/6-099') !== false) {
            $availability['fond'] = 'ZSGS';
        } elseif (strpos($name, 'Státní oblastní archiv v Plzni, pracoviště Klášter u Nepomuka, Rodinný archiv Windischgrätzů, inv. nr. 1433, karton nr. 202') !== false) {
            $availability['fond'] = 'Rodinný archiv Windischgrätzů';
        }

        if (strpos($name, 'Box num. 137, inv. n. 1195') !== false) {
            $availability['folder'] = 'Box num. 137, inv. n. 1195';
        } elseif (strpos($name, 'Slovak National Archive in Bratislava, f. MZV') !== false) {
            $availability['folder'] = trim(substr(strrchr($name, ','), 1, -1));
        } elseif (strpos($name, 'hstam_4_f_daenemark_nr_') === 0) {
            $availability['folder'] = 'Nr.' . substr($name, strlen('hstam_4_f_daenemark_nr_'));
        } elseif (strpos($name, 'hstam_4_f_schweden_nr_') === 0) {
            $availability['folder'] = 'Nr.' . substr($name, strlen('hstam_4_f_schweden_nr_'));
        } elseif (strpos($name, 'hstam_4_h_') === 0) {
            $availability['folder'] = 'Folder ' . substr($name, strlen('hstam_4_h_'));
        } elseif (strpos($name, 'hstam_5_nr_') === 0) {
            $availability['folder'] = 'Nr.' . substr($name, strlen('hstam_5_nr_'));
        } elseif (strpos($name, 'hstam_9_a_nr_') === 0) {
            $availability['folder'] = 'Nr.' . substr($name, strlen('hstam_9_a_nr_'));
        } elseif (strpos($name, 'hstam_115_01_nr_') === 0) {
            $availability['folder'] = 'Nr.' . substr($name, strlen('hstam_115_01_nr_'));
        } elseif (strpos($name, 'hstam_118_a_nr_') === 0) {
            $availability['folder'] = 'Nr.' . substr($name, strlen('hstam_118_a_nr_'));
        } elseif (strpos($name, 'hstam_300_nr_c_20_11') === 0) {
            $availability['folder'] = 'Nr. C 20/11';
        } elseif (strpos($name, 'hstam_4_d_nr_') === 0) {
            $availability['folder'] = 'Nr.' . substr($name, strlen('hstam_4_d_nr_'));
        } elseif (strpos($name, 'hstam_4 d_nr_') === 0) {
            $availability['folder'] = 'Nr.' . substr($name, strlen('hstam_4 d_nr_'));
        } elseif (strpos($name, 'Státní oblastní archiv Třeboň:') !== false) {
            $availability['folder'] = 'sign. 223.223, inv. č. 730, položka 721/5-7 (1619, šifrovaný dopis Jaquota hraběti Buquoyovi)';
        } elseif (strpos($name, 'Gallica, BnF fr.3789, f.3') !== false) {
            $availability['folder'] = 'Unknown';
        } elseif (strpos($name, 'ABS Praha, f. ZSGS, box BF388a, 27-19/6-099') !== false) {
            $availability['folder'] = 'box BF388a, 27-19/6-099';
        } elseif (strpos($name, 'Státní oblastní archiv v Plzni, pracoviště Klášter u Nepomuka, Rodinný archiv Windischgrätzů, inv. nr. 1433, karton nr. 202') !== false) {
            $availability['folder'] = 'inv. nr. 1433, karton nr. 202.';
        }

        if (strpos($name, 'hstam_3_nr_2474') !== false) {
            $availability['archive'] = 'Hessisches Staatsarchiv Marburg';
            $availability['fond'] = '3 - Kabinettsakten';
            $availability['folder'] = 'Nr.2474';
        }

        if (strpos($name, 'hstam_3_nr_2639') !== false) {
            $availability['archive'] = 'Hessisches Staatsarchiv Marburg';
            $availability['fond'] = '3 - Kabinettsakten';
            $availability['folder'] = 'Nr.2639';
        }

        if (strpos($name, 'hstam_3_nr_3123') !== false) {
            $availability['archive'] = 'Hessisches Staatsarchiv Marburg';
            $availability['fond'] = '3 - Kabinettsakten';
            $availability['folder'] = 'Nr.3123';
        }


        if (!isset($availability['archive'])) {
            $availability['availability'] = $name;
        }

        if (isset($availability['archive']) && isset($availability['fond']) && !isset($availability['folder'])) {
            $availability['folder'] = 'Unknown';
        }
        if (isset($availability['archive']) && !isset($availability['fond']) && !isset($availability['folder'])) {
            $availability['fond'] = 'Unknown';
            $availability['folder'] = 'Unknown';
        }

        return $availability;
    }

    static function prepareDatabase()
    {
        DB::setDefaultConnection('mysql_2');
    }
}
