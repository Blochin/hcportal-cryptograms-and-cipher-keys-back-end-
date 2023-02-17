<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CipherType extends Model
{
    protected $fillable = [
        'name',

    ];

    protected $dates = [];
    public $timestamps = false;

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/cipher-types/' . $this->getKey());
    }
}
