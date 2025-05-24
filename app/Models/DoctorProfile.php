<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Optional
use App\Models\User; // Added import
use App\Models\PatientProfile; // Added import
use App\Models\DoctorPatient; // Added import

class DoctorProfile extends Model
{
    use HasFactory; // Included HasFactory

    protected $table = 'doctor_profiles';
    protected $primaryKey = 'doctor_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false; // As per plan: only created_at managed by migration default

    protected $fillable = [
        'doctor_id',
        'license_no',
        'specialty',
        // 'created_at' // typically not mass assigned
    ];

    protected $casts = [
        'doctor_id' => 'string',
        'created_at' => 'datetime', // Cast if you want to use it as Carbon instance
    ];

    /**
     * Get the user that owns the doctor profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'doctor_id', 'user_id');
    }

    /**
     * The patients that this doctor is assigned to.
     */
    public function patients()
    {
        return $this->belongsToMany(PatientProfile::class, 'doctor_patients', 'doctor_id', 'patient_id')
                    ->using(DoctorPatient::class) // If DoctorPatient is a custom pivot model
                    ->withPivot('assigned_at');
    }
}
