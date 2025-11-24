<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    public $timestamps = false; // FIXED — no updated_at column

    protected $fillable = [
        'user_id',
        'model',
        'record_id',
        'action',
        'old_data',
        'new_data',
        'created_at', // optional
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
