<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    protected $fillable = [
        'short_name',
        'name',
        'country',
    ];

    public $timestamps = false;

    /* ************************ Relationships ************************* */

    public function fonds()
    {
        return $this->hasMany(Fond::class);
    }

    public function folders()
    {
        return $this->hasManyThrough(Folder::class, Fond::class);
    }
}
