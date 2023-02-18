<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigitalizedTranscription extends Model
{
    protected $fillable = [
        'cipher_key_id',
        'digitalized_version',
        'note',
        'digitalization_date',
        'created_by',

    ];


    protected $dates = [
        'digitalization_date',

    ];
    public $timestamps = false;

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/digitalized-transcriptions/' . $this->getKey());
    }

    /* ************************ RELATIONSHIPS ************************* */

    public function cipherKey()
    {
        return $this->belongsTo(CipherKey::class);
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function encryptionPairs()
    {
        return $this->hasMany(EncryptionPair::class);
    }
}
