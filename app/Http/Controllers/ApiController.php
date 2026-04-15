<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiController extends Controller
{
    public function login(Request $request){
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if($user instanceof User){
                if (!$user || !Hash::check($request->password, $user->password)) {

                    return response()->json(['message' => 'Invalid credentials'], 401);
                }

                return response()->json([
                    'token' => $user->createToken('api-token')->plainTextToken,
                ]);

            }else{
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

        }catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }
}
