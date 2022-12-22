<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type'];
    public $timestamps = false;

    public const CIPHER_KEY = 'cipher_key';
    public const CIPHER = 'cipher';

    public const TYPES = [
        ['id' => self::CIPHER_KEY, 'title' => 'Cipher key'],
        ['id' => self::CIPHER, 'title' => 'Cipher']
    ];

    protected $appends = ['resource_url', 'formatted_type'];

    /* ************************ Getters ************************* */

    /**
     * Resource url to generate edit
     *
     * @return UrlGenerator|string
     */
    public function getResourceUrlAttribute()
    {
        return url('/admin/tags/' . $this->getKey());
    }

    public function getFormattedTypeAttribute()
    {

        $tag = collect(self::TYPES)->where('id', $this->type)->first();
        return ($tag) ? $tag : null;
    }

    /* ************************ Relationships ************************* */

    public function posts()
    {
        return $this->morphedByMany(CipherKey::class, 'taggables');
    }
}
