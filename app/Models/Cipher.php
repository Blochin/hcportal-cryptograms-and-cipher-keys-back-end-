<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Brackets\Media\HasMedia\ProcessMediaTrait;
use Brackets\Media\HasMedia\AutoProcessMediaTrait;
use Brackets\Media\HasMedia\HasMediaCollectionsTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Brackets\Media\HasMedia\HasMediaThumbsTrait;
use Spatie\Image\Manipulations;

class Cipher extends Model implements HasMedia
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

    ];


    protected $dates = [];
    public $timestamps = false;

    protected $appends = ['resource_url'];

    /* ************************ Media ************************* */

    public function registerMediaCollections(Media $media = null): void
    {
        $this->addMediaCollection('thumbnail')
            ->accepts('image/*')
            ->maxNumberOfFiles(1)
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
        return url('/admin/ciphers/' . $this->getKey());
    }
}
