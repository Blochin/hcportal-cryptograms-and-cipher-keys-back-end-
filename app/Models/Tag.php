<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['tag_name', 'tag_id', 'taggable_id', 'taggable_type'];

    public const CIPHER_KEY = 'cipher_key';
    public const CIPHER = 'cipher';

    /* ************************ Relationships ************************* */

    public function posts()
    {
        return $this->morphedByMany(CipherKey::class, 'taggables');
    }
}
