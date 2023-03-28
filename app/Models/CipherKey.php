<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CipherKey extends Model
{
    protected $fillable = [
        'description',
        'name',
        'complete_structure',
        'used_chars',
        'category_id',
        'key_type',
        'used_from',
        'used_to',
        'used_around',
        'folder_id',
        'location_id',
        'language_id',
        'group_id',
        'state',
        'note',
        'created_by',
        'availability'
    ];


    protected $dates = [
        'used_from',
        'used_to',

    ];
    public $timestamps = true;

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

    public const STATUS_AWAITING = 'awaiting';
    public const STATUS_REVISE = 'revise';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    public const STATUSES = [
        ['id' => self::STATUS_AWAITING, 'title' => 'Awaiting', 'show' => true],
        ['id' => self::STATUS_REVISE, 'title' => 'Revise', 'show' => true],
        ['id' => self::STATUS_APPROVED, 'title' => 'Approved', 'show' => true],
        ['id' => self::STATUS_REJECTED, 'title' => 'Rejected', 'show' => true],
    ];

    protected $appends = ['resource_url', 'fond', 'archive', 'state_badge', 'used_to_formatted', 'used_from_formatted', 'continent', 'location_name', 'availability_type'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/cipher-keys/' . $this->getKey());
    }


    public function getAvailabilityTypeAttribute()
    {
        return $this->availability ? Cryptogram::AVAILABILITY_TYPE : Cryptogram::ARCHIVE_TYPE;
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

        $title = collect(CipherKey::STATUSES)->where('id', $this->state['id'])->first();
        return '<span class="badge badge-' . $this->state['id'] . '">' . $title['title'] . '</span>';
    }

    public function getUsedToFormattedAttribute()
    {
        return ($this->used_to) ? $this->used_to->format('d. m. Y') : '';
    }

    public function getUsedFromFormattedAttribute()
    {
        return ($this->used_from) ? $this->used_from->format('d. m. Y') : '';
    }

    public function getStateAttribute($value)
    {
        return collect(CipherKey::STATUSES)->firstWhere('id', $value);
    }


    public function getContinentAttribute()
    {
        $continent = collect(Location::CONTINENTS)->firstWhere('continent', 'Unknown');
        if ($this->location) {
            $continent = collect(Location::CONTINENTS)->firstWhere('name', $this->location->continent) ?: $continent;
        }
        return $continent;
    }

    public function getLocationNameAttribute()
    {
        return ($this->location) ? $this->location->name : null;
    }

    /* ************************ Scopes ************************* */

    public function scopeApproved($query)
    {
        return $query->where('state', self::STATUS_APPROVED);
    }

    /* ************************ Relationships ************************* */

    public function images()
    {
        return $this->hasMany(CipherKeyImage::class);
    }

    public function digitalizedTranscriptions()
    {
        return $this->hasMany(DigitalizedTranscription::class);
    }

    public function users()
    {
        return $this->hasMany(CipherKeyPerson::class);
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
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
