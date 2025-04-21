<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['login', 'register', 'welcome']);
    }

    // Register a new user
    public function register(Request $request)
    {
        // Check if the request is from an admin
        $currentUser = Auth::user();
        if (!$currentUser || !$currentUser->hasPermission('create')) {
            return response()->json([
                'message' => 'Access Denied: You do not have permission to create users',
                'error' => 'insufficient_permissions'
            ], 403);
        }

        $validated = $request->validate([
            'user_id' => 'required|integer|unique:users,user_id',
            'first_name' => 'required|string|max:80',
            'last_name' => 'required|string|max:80',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:' . implode(',', array_keys(Config::get('permissions.roles'))),
        ]);

        $user = User::create([
            'user_id' => $validated['user_id'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password_hash' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_email_verified' => false,
            'permissions' => []
        ]);

        // Send email verification notification
        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'User registered successfully. Please check your email for verification link.',
            'data' => $user
        ], 201);
    }

    // Login user
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password_hash)) {
            return response()->json([
                'message' => 'Access Denied: Invalid email or password',
                'error' => 'authentication_failed'
            ], 401);
        }

        if (!$user->is_email_verified) {
            return response()->json([
                'message' => 'Access Denied: Please verify your email before logging in',
                'error' => 'email_not_verified'
            ], 403);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ]);
    }

    // Logout user
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->tokens()->delete();
            return response()->json([
                'message' => 'Logged out successfully',
                'status' => 'success'
            ]);
        }

        return response()->json([
            'message' => 'Access Denied: No user logged in',
            'error' => 'no_active_session'
        ], 401);
    }

    // Welcome endpoint
    public function welcome()
    {
        return response()->json([
            'message' => 'Welcome to the API',
            'status' => 'success'
        ]);
    }

    // Get all users
    public function index()
    {
        if (!Auth::user()->hasPermission('read')) {
            return response()->json([
                'message' => 'Access Denied: You do not have permission to view users',
                'error' => 'insufficient_permissions'
            ], 403);
        }

        $users = User::all();
        return response()->json([
            'message' => 'Users retrieved successfully',
            'data' => $users,
            'count' => $users->count()
        ]);
    }

    // Show user by ID
    public function show(User $user)
    {
        if (!Auth::user()->hasPermission('read')) {
            return response()->json([
                'message' => 'Access Denied: You do not have permission to view user details',
                'error' => 'insufficient_permissions'
            ], 403);
        }

        return response()->json([
            'message' => 'User details retrieved successfully',
            'data' => $user
        ]);
    }

    // Update user
    public function update(Request $request, User $user)
    {
        if (!Auth::user()->hasPermission('update')) {
            return response()->json([
                'message' => 'Access Denied: You do not have permission to update users',
                'error' => 'insufficient_permissions'
            ], 403);
        }

        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:80',
            'last_name' => 'sometimes|string|max:80',
            'email' => 'sometimes|email|unique:users,email,' . $user->user_id . ',user_id',
            'phone' => 'sometimes|string|max:20',
            'password' => 'sometimes|string|min:8|confirmed',
            'role' => 'sometimes|in:' . implode(',', array_keys(Config::get('permissions.roles'))),
            'is_email_verified' => 'sometimes|boolean',
            'permissions' => 'sometimes|array',
            'permissions.*' => 'string'
        ]);

        if (isset($validated['password'])) {
            $validated['password_hash'] = Hash::make($validated['password']);
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    // Delete user
    public function destroy(User $user)
    {
        if (!Auth::user()->hasPermission('delete')) {
            return response()->json([
                'message' => 'Access Denied: You do not have permission to delete users',
                'error' => 'insufficient_permissions'
            ], 403);
        }

        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully',
            'status' => 'success'
        ]);
    }

    // Add permission to user
    public function addPermission(Request $request, User $user)
    {
        if (!Auth::user()->hasPermission('manage_permissions')) {
            return response()->json([
                'message' => 'Access Denied: You do not have permission to manage permissions',
                'error' => 'insufficient_permissions'
            ], 403);
        }

        $validated = $request->validate([
            'permission' => 'required|string'
        ]);

        if (!$user->addPermission($validated['permission'])) {
            return response()->json([
                'message' => 'Failed to add permission. The permission might already exist or be part of the user\'s role.',
                'error' => 'permission_add_failed'
            ], 400);
        }

        return response()->json([
            'message' => 'Permission added successfully',
            'data' => $user
        ]);
    }

    // Remove permission from user
    public function removePermission(Request $request, User $user)
    {
        if (!Auth::user()->hasPermission('manage_permissions')) {
            return response()->json([
                'message' => 'Access Denied: You do not have permission to manage permissions',
                'error' => 'insufficient_permissions'
            ], 403);
        }

        $validated = $request->validate([
            'permission' => 'required|string'
        ]);

        $user->removePermission($validated['permission']);

        return response()->json([
            'message' => 'Permission removed successfully',
            'data' => $user
        ]);
    }
}
