<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shift extends Model
{
    protected $fillable = ['employee_id', 'start_time', 'end_time'];

    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
}