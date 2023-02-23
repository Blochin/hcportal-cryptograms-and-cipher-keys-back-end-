<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    public const CONTINENTS = [
        ['name' => 'Unknown'],
        ['name' => 'North America'],
        ['name' => 'South America'],
        ['name' => 'Europe'],
        ['name' => 'Asia'],
        ['name' => 'Oceania'],
        ['name' => 'Africa'],
        ['name' => 'Antartica'],
    ];

    protected $fillable = [
        'name',
        'continent'
    ];

    public $timestamps = false;

    protected $hidden = ['resource_url'];

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    /**
     * Resource url to generate edit
     *
     * @return UrlGenerator|string
     */
    public function getResourceUrlAttribute()
    {
        return url('/admin/locations/' . $this->getKey());
    }
}
