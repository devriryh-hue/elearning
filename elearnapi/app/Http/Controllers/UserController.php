<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function getAllUsers()
    {
        $users = Cache::remember('users_list', 60, function () {
            return User::all();
        });

        return response()->json($users);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $page = request('page', 1);
        $perPage = request('per_page', 5);
        $role = request('role', 'professor'); // Default role is 'professor'

        $users = Cache::remember(
            "users_page_{$page}_perpage_{$perPage}_role_{$role}",
            60,
            function () use ($role, $perPage) {
                return User::where('role', $role)->paginate($perPage);
            }
        );

        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'role' => 'required',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role']
        ]);
        //Ajout de l'avatar
        if ($request->hasFile('avatar')) {
            $user->addMediaFromRequest('avatar')
                ->toMediaCollection('avatar');
        }
        $user->avatar = $user->getFirstMediaUrl('avatar');
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
            'avatar' => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
        ]);

        $user->update($validated);

        if ($request->hasFile('avatar')) {
            $user
                ->addMediaFromRequest('avatar')
                ->toMediaCollection('avatar');
        }

        return response()->json($user);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
        $user->delete();

        return response()->json([
            'message' => 'Utilisateur supprimé'
        ]);
    }
}
