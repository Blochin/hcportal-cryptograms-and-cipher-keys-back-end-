<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CipherKeySimilarity extends Model
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
        return url('/admin/cipher-key-similarities/' . $this->getKey());
    }

    public function cipherKeys()
    {
        return $this->belongsToMany(CipherKey::class, 'cipher_key_similarity', 'cipher_key_similarity_id', 'cipher_key_id');
    }
}
