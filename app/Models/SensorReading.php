<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Optional
use App\Models\PatientProfile; // Added import

class SensorReading extends Model
{
    use HasFactory; // Included HasFactory as it's generally good practice

    protected $table = 'sensor_readings';
    protected $primaryKey = 'reading_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false; // Migration manages 'timestamp', not Eloquent's created_at/updated_at

    protected $fillable = [
        'reading_id', // Since it's a UUID, it might be set on creation
        'patient_id',
        'bed_id',
        'sensor_type',
        'sensor_value',
        'sensor_unit',
        'timestamp',
        'additional_metadata',
    ];

    protected $casts = [
        'reading_id' => 'string',
        'patient_id' => 'string',
        'sensor_value' => 'float',
        'timestamp' => 'datetime',
        'additional_metadata' => 'json',
        // 'sensor_type' is an ENUM, typically treated as string by Eloquent
    ];

    /**
     * Get the patient profile that owns this sensor reading.
     */
    public function patientProfile()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id', 'patient_id');
    }
}
