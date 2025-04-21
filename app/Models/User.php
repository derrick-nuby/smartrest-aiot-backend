<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'integer';

<<<<<<< HEAD
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
<<<<<<< HEAD
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

=======
    // Make sure User model is fillable
    
=======
>>>>>>> 7aa654f (feat: implement dafault and custom permission & role management with CRUD, user authentication and authorization)
    protected $fillable = [
        'user_id',
        'email',
        'password_hash',
        'role',
        'first_name',
        'last_name',
        'phone',
        'is_email_verified',
        'permissions'
    ];

<<<<<<< HEAD

>>>>>>> f945b34 (Initial commit with Docker support)
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
=======
>>>>>>> 7aa654f (feat: implement dafault and custom permission & role management with CRUD, user authentication and authorization)
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected $casts = [
        'is_email_verified' => 'boolean',
        'permissions' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function hasVerifiedEmail()
    {
        return $this->is_email_verified;
    }

    public function markEmailAsVerified()
    {
        $this->is_email_verified = true;
        $this->save();
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmail);
    }

    public function hasPermission($permission)
    {
        // Admins have all permissions
        if ($this->role === 'admin') {
            return true;
        }

        // Get role-based permissions from config
        $rolePermissions = Config::get('permissions.roles.' . $this->role, []);

        // Check if the permission is in the role's default permissions
        if (in_array($permission, $rolePermissions)) {
            return true;
        }

        // Check if the permission is in the user's custom permissions
        $customPermissions = $this->getCustomPermissions();
        if (in_array($permission, $customPermissions)) {
            return true;
        }

        return false;
    }

    public function addPermission($permission)
    {
        // Only admins can add permissions
        if (Auth::user()->role !== 'admin') {
            return false;
        }

        // Get role-based permissions from config
        $rolePermissions = Config::get('permissions.roles.' . $this->role, []);

        // Don't allow adding permissions that are already in the role's permissions
        if (in_array($permission, $rolePermissions)) {
            return false;
        }

        $permissions = $this->getCustomPermissions();
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->attributes['permissions'] = json_encode($permissions);
            $this->save();
            return true;
        }
        return false;
    }

    public function removePermission($permission)
    {
        // Only admins can remove permissions
        if (Auth::user()->role !== 'admin') {
            return false;
        }

        // Get role-based permissions from config
        $rolePermissions = Config::get('permissions.roles.' . $this->role, []);

        // Don't allow removing permissions that are in the role's permissions
        if (in_array($permission, $rolePermissions)) {
            return false;
        }

        $permissions = $this->getCustomPermissions();
        $this->attributes['permissions'] = array_values(array_diff($permissions, [$permission]));
        $this->save();
        return true;
    }

    protected function getCustomPermissions()
    {
        if (!isset($this->attributes['permissions'])) {
            return [];
        }

        $permissions = json_decode($this->attributes['permissions'], true);
        return is_array($permissions) ? $permissions : [];
    }

    public function getPermissionsAttribute()
    {
        // Get role-based permissions from config
        $rolePermissions = Config::get('permissions.roles.' . $this->role, []);

        // Get custom permissions from database
        $customPermissions = $this->getCustomPermissions();

        // Merge and return unique permissions
        return array_unique(array_merge($rolePermissions, $customPermissions));
    }
}