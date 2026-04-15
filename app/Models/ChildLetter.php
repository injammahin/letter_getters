<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildLetter extends Model
{
    protected $fillable = [
        'child_match_id',
        'sender_user_id',
        'receiver_user_id',
        'subject',
        'body',
        'status',
        'read_at',
        'reviewed_by',
        'reviewed_at',
        'approved_at',
        'admin_notes',
        'scan_status',
        'scan_flagged',
        'scan_hits',
        'scan_summary',
        'scanned_by',
        'scanned_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'scanned_at' => 'datetime',
        'scan_flagged' => 'boolean',
        'scan_hits' => 'array',
    ];

    public function match()
    {
        return $this->belongsTo(ChildMatch::class, 'child_match_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scanner()
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }

    public function isCleanScanned(): bool
    {
        return $this->scan_status === 'scanned' && ! $this->scan_flagged;
    }

    public function isFlaggedScanned(): bool
    {
        return $this->scan_status === 'scanned' && $this->scan_flagged;
    }
}