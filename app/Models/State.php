<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    public const STATUS_NEW = 'new';
    public const STATUS_AWAITING = 'awaiting';
    public const STATUS_REVISE = 'revise';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_DELETED = 'deleted';
    public const STATUS_REJECTED = 'rejected';

    public const STATUSES = [
        ['id' => self::STATUS_NEW, 'title' => 'New', 'show' => false],
        ['id' => self::STATUS_AWAITING, 'title' => 'Awaiting', 'show' => true],
        ['id' => self::STATUS_REVISE, 'title' => 'Revise', 'show' => true],
        ['id' => self::STATUS_APPROVED, 'title' => 'Approved', 'show' => true],
        ['id' => self::STATUS_DELETED, 'title' => 'Deleted', 'show' => true],
        ['id' => self::STATUS_REJECTED, 'title' => 'Rejected', 'show' => true],
    ];

    protected $fillable = [
        'name',
        'state',
        'note',
        'created_by'
    ];
}
