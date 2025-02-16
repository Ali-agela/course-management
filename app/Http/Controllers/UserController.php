<?php

namespace App\Http\Controllers;

use Auth;
use Gate;
use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Sanctum\HasApiTokens;

class UserController extends Controller
{
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

            if (auth()->user()->role === 'admin') {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'role' => $request->role,
                ]);
            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role,
            ]);
        }
        return response()->json($user);
    }

    public function login(Request $request)
    {
        // Validateing  the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        //checking if the user exists
        $user = User::where('email', $request->email)->first();
        if (!$user || !\Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        //generating the token
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['access_token' => $token]);
    }
    public function logout(Request $request)
    {
        //revoking the token
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function user(Request $request)
    {
        return $request->user();
    }

    public function update(Request $request)
    {
        // Validateing  the request data
        $input = $request->validate([
            'name' => ['string'],
            'email' => ['email'],

        ]);

        $user = auth()->user();
        $user->update($input);
        return response()->json($user);
    }

    public function delete()
    {
        $user = auth()->user();
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }

    public function users(Request $request){

        if(auth()->user()->role !== 'admin'){
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $users = User::all();

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
            $users =   $users->where('role', $request->role);
        }

        if($request->has('id')){
            $users =   $users->where('id', $request->id);
        }
        return response()->json($users);
    }
}
