<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Keshav extends BaseModel
{
    use SoftDeletes;

    protected $table = 'keshav';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'gender',
        'phone',
        'address',
        'occupation_field',
        'experience',
        'mode_of_transfer',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'gender'      => 'integer',
        'experience'  => 'integer',
        'created_by'  => 'integer',
        'updated_by'  => 'integer',
        'deleted_by'  => 'integer',
        'deleted_at'  => 'datetime',
    ];

    /**
     * Automatically set created_by, updated_by, deleted_by
     */
    protected static function boot()
    {
        parent::boot();

        $userId = Auth::check() ? Auth::id() : 1; // fallback to admin (ID 1) if no auth

        static::creating(function ($model) use ($userId) {
            $model->created_by = $userId;
            $model->updated_by = $userId;
        });

        static::updating(function ($model) use ($userId) {
            $model->updated_by = $userId;
        });

        static::deleting(function ($model) use ($userId) {
            $model->deleted_by = $userId;
            $model->save(); // Important: save before soft delete
        });
    }

    /**
     * Accessor: Get gender as text
     * Use: $user->gender_text
     */
    public function getGenderTextAttribute(): string
    {
        return $this->gender == 1 ? 'Male' : 'Female';
    }

    /**
     * Optional: Scope to get only active (non-deleted) users
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Optional: Relationship with creator (if you have User model)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
