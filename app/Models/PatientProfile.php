<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Optional: if you plan to use factories
use App\Models\User;
use App\Models\SensorReading;
use App\Models\DoctorProfile;
use App\Models\DoctorPatient; // Pivot model

class PatientProfile extends Model
{
    use HasFactory; // Enabling factory as it's generally useful

    protected $table = 'patient_profiles';
    protected $primaryKey = 'patient_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false; // Only created_at is managed by migration default

    protected $fillable = [
        'patient_id',
        'national_id',
        'date_of_birth',
        'sex',
        // 'created_at' is not mass assignable
    ];

    protected $casts = [
        'patient_id' => 'string',
        'date_of_birth' => 'date',
        'created_at' => 'datetime', // Cast to Carbon instance for easier manipulation
    ];

    /**
     * Get the user that owns the patient profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'patient_id', 'user_id');
    }

    /**
     * Get the sensor readings for the patient.
     */
    public function sensorReadings()
    {
        return $this->hasMany(SensorReading::class, 'patient_id', 'patient_id');
    }

    /**
     * The doctors that are assigned to this patient.
     */
    public function doctors()
    {
        return $this->belongsToMany(DoctorProfile::class, 'doctor_patients', 'patient_id', 'doctor_id')
                    ->using(DoctorPatient::class) // Specify custom pivot model
                    ->withPivot('assigned_at');
    }
}
