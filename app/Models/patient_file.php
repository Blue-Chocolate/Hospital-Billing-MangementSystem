<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'filename',
        'path',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
   
}
