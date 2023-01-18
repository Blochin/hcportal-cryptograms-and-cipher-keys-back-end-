<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CipherKey extends Model
{
    protected $fillable = [
        'description',
        'signature',
        'complete_structure',
        'used_chars',
        'cipher_type',
        'key_type',
        'used_from',
        'used_to',
        'used_around',
        'folder_id',
        'location_id',
        'language_id',
        'group_id',
        'state_id',
        'created_by'

    ];


    protected $dates = [
        'used_from',
        'used_to',

    ];
    public $timestamps = false;

    public const CIPHER_TYPES = [
        ['id' => 'undefined', 'label' => 'Undefined'],
        ['id' => 'nomenclator', 'label' => 'Nomenclator'],
        ['id' => 'code', 'label' => 'Code'],
        ['id' => 'substitution', 'label' => 'Simple substitution']
    ];

    public const KEY_TYPES = [
        ['id' => 'e', 'label' => 'e'],
        ['id' => 'ed', 'label' => 'ed'],
    ];

    protected $appends = ['resource_url', 'fond', 'archive', 'state_badge', 'used_to_formatted', 'used_from_formatted'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/cipher-keys/' . $this->getKey());
    }


    public function getFondAttribute()
    {
        return isset($this->folder->fond) ? $this->folder->fond : null;
    }

    public function getArchiveAttribute()
    {
        return isset($this->folder->fond) ? $this->folder->fond->archive : null;
    }

    public function getStateBadgeAttribute()
    {
        if (!isset($this->state)) return null;

        $title = collect(State::STATUSES)->where('id', $this->state->state)->first();
        return '<span class="badge badge-' . $this->state->state . '">' . $title['title'] . '</span>';
    }

    public function getUsedToFormattedAttribute()
    {
        return ($this->used_to) ? $this->used_to->format('d. m. Y') : '';
    }

    public function getUsedFromFormattedAttribute()
    {
        return ($this->used_from) ? $this->used_from->format('d. m. Y') : '';
    }



    /* ************************ Relationships ************************* */

    public function images()
    {
        return $this->hasMany(CipherKeyImage::class);
    }

    public function users()
    {
        return $this->hasMany(CipherKeyPerson::class);
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function cipherType()
    {
        return $this->belongsTo(CipherType::class, 'cipher_type');
    }

    public function keyType()
    {
        return $this->belongsTo(KeyType::class, 'key_type');
    }


    public function group()
    {
        return $this->belongsTo(CipherKey::class);
    }

    public function keySimilarities()
    {
        return $this->belongsToMany(CipherKeySimilarity::class, 'cipher_key_similarity', 'cipher_key_id', 'cipher_key_similarity_id');
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function cryptograms()
    {
        return $this->belongsToMany(Cryptogram::class, 'cipher_key_cryptogram', 'cipher_key_id', 'cryptogram_id',);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
