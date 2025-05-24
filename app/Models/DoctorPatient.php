<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DoctorPatient extends Pivot
{
    protected $table = 'doctor_patients';

    // No primary key definition needed for a basic pivot model unless it has its own ID.
    // The composite primary key is on the table, Eloquent handles it for relationships.

    // public $incrementing = true; // Removed as per instruction

    public $timestamps = false; // The table only has `assigned_at`, not `created_at`/`updated_at`.

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'assigned_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'doctor_id' => 'string', // Assuming UUIDs are stored as strings
        'patient_id' => 'string',// Assuming UUIDs are stored as strings
    ];

    // Relationships to the models on either side of the pivot, if needed for direct queries on DoctorPatient instances
    // public function doctor()
    // {
    //     return $this->belongsTo(DoctorProfile::class, 'doctor_id', 'doctor_id');
    // }

    // public function patient()
    // {
    //     return $this->belongsTo(PatientProfile::class, 'patient_id', 'patient_id');
    // }
}
