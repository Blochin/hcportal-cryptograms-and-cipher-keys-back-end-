<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'fond_id',
        'start_date',
        'end_date',
    ];

    public $timestamps = false;

    /* ************************ Relationships ************************* */

    public function fond()
    {
        return $this->belongsTo(Fond::class);
    }

    public function archives()
    {
        return $this->hasManyThrough(Archive::class, Fond::class);
    }
}
