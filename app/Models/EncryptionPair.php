<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EncryptionPair extends Model
{
    protected $fillable = [
        'digitalized_transcription_id',
        'plain_text_unit',
        'cipher_text_unit',

    ];

    public $timestamps = false;


    /* ************************ RELATIONSHIPS ************************* */
    public function digitalizedTranscription()
    {
        return $this->belongsTo(DigitalizedTranscription::class);
    }
}
