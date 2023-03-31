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

class Data extends Model implements HasMedia
{
    use HasFactory;
    use ProcessMediaTrait;
    use AutoProcessMediaTrait;
    use HasMediaCollectionsTrait;
    use HasMediaThumbsTrait;

    protected $fillable = [
        'blob',
        'description',
        'datagroup_id',
        'filetype',
        'dl_protection'
    ];

    public const TYPES = [
        ['id' => 'link', 'name' => 'Link'],
        ['id' => 'text', 'name' => 'Text'],
        ['id' => 'image', 'name' => 'Image']
    ];

    public $timestamps = false;

    protected $appends = ['image', 'type', 'link', 'text', 'title'];

    /* ************************ Media ************************* */

    public function registerMediaCollections(Media $media = null): void
    {
        $this->addMediaCollection('image')
            ->accepts('*')
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

    public function getImageAttribute()
    {
        return $this->getFirstMediaUrl('image', 'big') ?: null;
    }

    public function getLinkAttribute()
    {
        return $this->blobb;
    }


    public function getTitleAttribute()
    {
        return $this->description;
    }


    public function getTextAttribute()
    {
        return $this->blobb;
    }

    public function getTypeAttribute()
    {
        return collect(self::TYPES)->where('id', $this->filetype)->first();
    }
    /* ************************ Relationships ************************* */

    public function fond()
    {
        return $this->belongsTo(Datagroup::class);
    }
}
