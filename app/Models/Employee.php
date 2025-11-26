<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employee_performance';   // your table name

    protected $primaryKey = 'emp_id';

    public $incrementing = false;   // because emp_id is manually entered

    protected $keyType = 'int';

    protected $fillable = [
        'emp_id',
        'name',
        'department',
        'country',
        'tasks',
        'hours',
        'leaves',
        'efficiency',
        'attendance',
        'rating',
    ];
}
