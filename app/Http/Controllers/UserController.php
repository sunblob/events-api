<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Exceptions\NotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

final class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json([
            'data' => $users,
            'count' => $users->count(),
        ]);
    }

    public function editors()
    {
        $editors = User::where('role', 'editor')->get();
        return response()->json([
            'data' => $editors,
            'count' => $editors->count(),
        ]);
    }

    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            throw new NotFoundException('User not found');
        }

        return response()->json([
            'data' => $user,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => ['required', 'string', Rule::in(['admin', 'editor'])],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return response()->json([
            'data' => $user,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            throw new NotFoundException('User not found');
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'sometimes|string|min:8',
            'role' => ['sometimes', 'string', Rule::in(['admin', 'editor'])],
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $updated = $user->update($validated);

        if (!$updated) {
            return response()->json([
                'message' => 'Failed to update user',
            ], 500);
        }

        return response()->json([
            'data' => $user,
        ]);
    }

    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            throw new NotFoundException('User not found');
        }

        $deleted = $user->delete();

        if (!$deleted) {
            return response()->json([
                'message' => 'Failed to delete user',
            ], 500);
        }

        return response()->json([
            'message' => 'User deleted successfully',
        ]);
    }
}
