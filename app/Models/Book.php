<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogActivityTrait;   // ✅ This is the correct trait

class Book extends Model
{
    use HasFactory, SoftDeletes, LogActivityTrait;   // ✅ Trait applied here

    protected $table = 'books';

    // Primary key is isbn, not id
    protected $primaryKey = 'isbn';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'title',
        'author',
        'genre',
        'isbn',
        'publisher',
        'publication_year',
        'total_copies',
        'available_copies',
        'issued_by',
    ];

    protected $casts = [
        'publication_year' => 'integer',
        'total_copies'     => 'integer',
        'available_copies' => 'integer',
        'deleted_at'       => 'datetime',
    ];
}
