<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Brackets\Media\HasMedia\ProcessMediaTrait;
use Brackets\Media\HasMedia\AutoProcessMediaTrait;
use Brackets\Media\HasMedia\HasMediaCollectionsTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Brackets\Media\HasMedia\HasMediaThumbsTrait;
use Spatie\Image\Manipulations;

class CipherKeyImage extends Model implements HasMedia
{

    protected $table = "cipher_keys_images";
    public $timestamps = false;

    use HasFactory;
    use ProcessMediaTrait;
    use AutoProcessMediaTrait;
    use HasMediaCollectionsTrait;
    use HasMediaThumbsTrait;


    protected $fillable = [
        'url',
        'cipher_key_id',
        'is_local',
        'structure',
        'ordering',
        'has_instructions'
    ];

    protected $appends = ['picture'];


    /* ************************ Media ************************* */

    public function registerMediaCollections(Media $media = null): void
    {
        $this->addMediaCollection('picture')
            ->accepts('image/*')
            ->maxNumberOfFiles(1)
            ->maxFilesize(100 * 1024 * 1024); // Set the file size limit

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

    /* ************************ Getters and setters ************************* */

    public function getPictureAttribute()
    {
        return $this->getFirstMediaUrl('picture', 'big') ?: null;
    }
}
