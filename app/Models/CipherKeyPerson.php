<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CipherKeyPerson extends Model
{
    use HasFactory;

    protected $table = 'cipher_key_persons';
    public $timestamps = false;

    protected $fillable = [
        'person_id',
        'cipher_key_id',
        'is_main_user',
    ];

    protected $appends = ['user'];


    /* ************************ Getters and setters ************************* */

    public function getUserAttribute()
    {
        return $this->person;
    }

    /* ************************ Relationships ************************* */

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function cipherKey()
    {
        return $this->belongsTo(CipherKey::class);
    }
}
