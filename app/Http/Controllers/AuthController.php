<?php

namespace App\Http\Controllers;

use App\Models\User;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|unique:users,name',
                'password' => 'required_with:password_confirmation|min:6|same:password_confirmation',
                'password_confirmation' => 'required'
            ]);
            $token = Str::uuid()->toString();
            $user = User::create([
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'token' => $token
            ]);

            return response()->json([
                'message' => 'Register Success',
                'token' => $token
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'invalid',
                'message' => $th->getMessage()
            ], 400);
        }
    }

    public function login(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'password' => 'required'
            ]);
            $user = User::where('name', $request->name)->first();
            if ($user == null || !Hash::check($request->password, $user->password)) {
                throw new Error('Username or Password Incorrect', 401);
            }
            $toket = Str::uuid()->toString();
            $user->update([
                'token' => $toket
            ]);
            return response()->json([
                'status' => 'success',
                'toket gede' => $toket
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'invalid',
                'message' => $th->getMessage()
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        $authToken = $request->header('Authorization');
        $token = explode(' ', $authToken);
        $uuidToken = $token[1];
        $user = User::where('token', $uuidToken)->first();
        $user->update([
                'token' => null
        ]);
        if($user) {
            return response()->json([
                'message' => 'Logout success'
            ]);
        }
    }
}
