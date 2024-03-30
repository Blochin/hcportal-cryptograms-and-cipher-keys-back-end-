<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    const ACTION_CREATED = 'created';
    const ACTION_UPDATED = 'updated';
    const ACTION_DELETED = 'deleted';

    protected $fillable = ['action', 'causer_id', 'data', 'loggable_id', 'loggable_type'];

    public function loggable()
    {
        return $this->morphTo();
    }

    public function causer()
    {
        return $this->belongsTo(User::class, 'causer_id');
    }
}
