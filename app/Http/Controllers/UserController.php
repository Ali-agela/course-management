<?php

namespace App\Http\Controllers;

use Auth;
use Gate;
use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Sanctum\HasApiTokens;

class UserController extends Controller
{

    // Register a new user 
    public function register(Request $request)
    {
        // Validateing  the request data
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:student,instructor,admin',
        ]);

        //checking if the new user role only admins can make other admins 
        if ($request->role === 'admin') {

            //checking if the user is an admin,  if is create if not return unauthorized
            if (auth()->check()) {
                if (auth()->user()->role === 'admin') {
                    $user = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => bcrypt($request->password),
                        'role' => $request->role,
                    ]);
                }

            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

        } else {//creating a new user with the role student or instructor
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role,
            ]);
        }
        return response()->json($user, 201);
    }


    // Login a user
    public function login(Request $request)
    {
        // Validateing  the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //checking if the user exists
        $user = User::where('email', $request->email)->first();

        //checking if the user exists and the password is correct
        if (!$user || !\Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        //generating the token
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['access_token' => $token]);
    }

    // Logout a user
    public function logout(Request $request)
    {
        //revoking the token
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    // Get the authenticated user
    public function user(Request $request)
    {
        //returning the authenticated user
        return $request->user();
    }

    // Update the authenticated user
    public function update(Request $request)
    {
        // Validateing  the request data
        $input = $request->validate([
            'name' => ['string'],
            'email' => ['email', 'unique:users,email'],

        ]);

        //updating the user
        $user = auth()->user();
        $user->update($input);
        return response()->json($user);
    }

    // Delete the a user
    public function delete(Request $request)
    {
        //the admin can delete any other user by passing the id in the request
        if (auth()->user()->role === 'admin' && $request->has('id')) {
            $user = User::findOrFail($request->id);
            $user->delete();
            return response()->json(['message' => 'User deleted']);

        }

        //the user can delete his own account
        $user = auth()->user();
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }



    // Get all users for admins only
    public function users(Request $request)
    {

        //checking if the user is an admin if not return unauthorized
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        //getting all the users
        $users = User::all();

        //filtering the users based on the request data
        // name , email , role and id
        if ($request->has('name')) {
            $users = $users->filter(function ($user) use ($request) {
                return stripos($user->name, $request->name) !== false;
            });
        }

        if ($request->has('email')) {
            $users = $users->filter(function ($user) use ($request) {
                return stripos($user->email, $request->email) !== false;
            });
        }

        if ($request->has('role')) {
            $users = $users->where('role', $request->role);
        }

        if ($request->has('id')) {
            $users = $users->where('id', $request->id);
        }

        return response()->json($users);
    }
}
