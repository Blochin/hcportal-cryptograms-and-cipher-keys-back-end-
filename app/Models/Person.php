<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'persons';
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];


    /* ************************ Relationships ************************* */

    public function cipherKeys()
    {
        return $this->hasManyThrough(
            CipherKey::class,
            CipherKeyPerson::class,
            'person_id', // foreign key on CipherKeyPerson table
            'id', // local key on CipherKey table
            'id', // local key on CipherKeyPerson table
            'cipher_key_id' // foreign key on Person table
        );
    }

    public function senderCryptograms()
    {
        return $this->hasMany(Cryptogram::class, 'sender_id', 'id');
    }

    public function recipientCryptograms()
    {
        return $this->hasMany(Cryptogram::class, 'recipient_id', 'id');
    }
}
