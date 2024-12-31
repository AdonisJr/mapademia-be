<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // Import Log facade
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'firstname' => 'nullable|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'gender' => 'nullable|string',
            'email' => 'nullable|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:3',
            'year_level' => 'nullable|string',
            'course' => 'nullable|string|max:20',
            'role' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:20',
        ]);

        // Find the user by ID
        $user = User::findOrFail($id);

        // Update user fields
        $user->fill($validatedData);

        // Hash the password if it's provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Save the changes to the database
        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function changePassword(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'current_password' => 'required|string|min:3',
            'new_password' => 'required|string|min:3|confirmed', // new password must be confirmed
        ]);

        // Get the currently authenticated user
        $user = Auth::user();

        // Check if the provided current password matches the stored password
        if (!Hash::check($validatedData['current_password'], $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 400);
        }

        // Hash and update the new password
        $user->password = Hash::make($validatedData['new_password']);
        $user->save();

        return response()->json([
            'message' => 'Password updated successfully',
        ], 200);
    }
}
