<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fond extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'archive_id',
    ];

    public $timestamps = false;

    /* ************************ Relationships ************************* */

    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function archive()
    {
        return $this->belongsTo(Archive::class);
    }
}
