<?php

namespace App\Models;

use Brackets\Media\Exceptions\FileCannotBeAdded\TooManyFiles;
use Illuminate\Database\Eloquent\Model;
use Brackets\Media\HasMedia\ProcessMediaTrait;
use Brackets\Media\HasMedia\AutoProcessMediaTrait;
use Brackets\Media\HasMedia\HasMediaCollectionsTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Brackets\Media\HasMedia\HasMediaThumbsTrait;
use Brackets\Media\HasMedia\MediaCollection;
use Illuminate\Support\Collection;
use Spatie\Image\Manipulations;

class Cryptogram extends Model implements HasMedia
{
    use ProcessMediaTrait;
    use AutoProcessMediaTrait;
    use HasMediaCollectionsTrait;
    use HasMediaThumbsTrait;

    protected $fillable = [
        'availability',
        'category_id',
        'day',
        'description',
        'flag',
        'image_url',
        'language_id',
        'location_id',
        'month',
        'name',
        'recipient_id',
        'sender_id',
        'solution_id',
        'state_id',
        'year',
        'created_by',
        'state',
        'note'
    ];


    protected $dates = [];


    protected $appends = ['resource_url', 'state_badge', 'continent', 'location_name', 'picture'];

    /* ************************ Media ************************* */

    public function registerMediaCollections(Media $media = null): void
    {
        $this->addMediaCollection('picture')
            ->accepts('image/*')
            ->singleFile()
            ->maxFilesize(10 * 1024 * 1024); // Set the file size limit

    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->autoRegisterThumb200();

        $this->addMediaConversion('thumb')
            ->optimize()
            ->width(368)
            ->nonQueued();

        $this->addMediaConversion('big')
            ->optimize()
            ->width(600)
            ->nonQueued();

        $this->addMediaConversion('large')
            ->optimize()
            ->width(1024)
            ->nonQueued();

        $this->addMediaConversion('crop')
            ->optimize()
            ->crop(Manipulations::CROP_CENTER, 250, 250)
            ->nonQueued();
    }

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/cryptograms/' . $this->getKey());
    }

    public function getPictureAttribute()
    {
        return $this->getFirstMediaUrl('picture', 'big') ?: null;
    }

    public function getStateBadgeAttribute()
    {
        if (!isset($this->state)) return null;

        $title = collect(CipherKey::STATUSES)->where('id', $this->state['id'])->first();
        return '<span class="badge badge-' . $this->state['id'] . '">' . $title['title'] . '</span>';
    }

    public function getStateAttribute($value)
    {
        return collect(CipherKey::STATUSES)->firstWhere('id', $value);
    }

    public function getContinentAttribute()
    {
        $continent = collect(Location::CONTINENTS)->firstWhere('continent', 'Unknown');
        if ($this->location) {
            $continent = collect(Location::CONTINENTS)->firstWhere('name', $this->location->continent) ?: $continent;
        }
        return $continent;
    }

    public function getLocationNameAttribute()
    {
        return ($this->location) ? $this->location->name : null;
    }

    /* ************************ Scopes ************************* */

    public function scopeApproved($query)
    {
        return $query->where('state', CipherKey::STATUS_APPROVED);
    }

    /* ************************ Relationships ************************* */

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function sender()
    {
        return $this->belongsTo(Person::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function recipient()
    {
        return $this->belongsTo(Person::class);
    }

    public function solution()
    {
        return $this->belongsTo(Solution::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->with('children');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function groups()
    {
        return $this->hasMany(Datagroup::class);
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }


    public function cipherKeys()
    {
        return $this->belongsToMany(CipherKey::class, 'cipher_key_cryptogram', 'cryptogram_id', 'cipher_key_id');
    }

    public function processMedia(Collection $inputMedia): void
    {
        //        Don't we want to use maybe some class to represent the data structure?
        //        Maybe what we want is a MediumOperation class, which holds {collection name, operation (detach, attach, replace), metadata, filepath)} what do you think?

        $inputMedia = $inputMedia->toArray();
        if (isset($inputMedia['picture'])) {
            $inputMedia['picture'] = collect(json_decode($inputMedia['picture']))->map(function ($item) {
                $item = collect($item)->toArray();
                if (isset($item['meta_data'])) {
                    $item['meta_data'] = collect($item['meta_data'])->toArray();
                }
                return $item;
            })->toArray();

            $inputMedia = collect($inputMedia);


            // //First validate input
            // $this->getMediaCollections()->each(function ($mediaCollection) use ($inputMedia) {
            //     $this->validate(collect($inputMedia->get($mediaCollection->getName())), $mediaCollection);
            // });

            //Then process each media
            $this->getMediaCollections()->each(function ($mediaCollection) use ($inputMedia) {
                collect($inputMedia->get($mediaCollection->getName()))->each(function ($inputMedium) use (
                    $mediaCollection
                ) {
                    $this->processMedium($inputMedium, $mediaCollection);
                });
            });
        }
    }
}
