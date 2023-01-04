<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datagroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'cryptogram_id',
    ];

    public $timestamps = false;

    /* ************************ Relationships ************************* */

    public function cryptogram()
    {
        return $this->belongsTo(Cryptogram::class);
    }

    public function data()
    {
        return $this->hasMany(Data::class);
    }
}
