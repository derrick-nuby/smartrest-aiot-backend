<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Concerns\HasUuids; // Removed as product_id is no longer UUID
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory; // Removed HasUuids

    protected $primaryKey  = 'product_id';
    public    $incrementing = false;      // IDs are not auto‑incrementing
    protected $keyType      = 'string';

    protected $fillable = [
        'product_id', // Added product_id
        'name',
        'description',
        'image_url',
        'firmware_version',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'product_id' => 'string', // Ensure product_id is treated as a string
    ];
}
