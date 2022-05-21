<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function createAccount(Request $request)
    {

        $validation = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|email:rfc,dns",
            "password" => "required|string|min:6|confirmed"
        ]);

        // if ($validation->fails()) {
        //     return response()->json(["errors" => $validation->errors()->all()], Response::HTTP_UNPROCESSABLE_ENTITY);
        // }

        $user = User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'email' => $request->email
        ]);
        // $token = $user->createToken('token')->plainTextToken;

        return \response()->json([
            "user_id" => $user->id,
        ]);
    }
    public function signin(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "email" => "required|email:rfc,dns",
            "password" => "required|string|min:6"
        ]);

        if ($validation->fails()) {
            return response()->json(["errors" => $validation->errors()->all()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::where("email", $request->email)->first();

        if (!$user) {
            return \response()->json(["errors" => ["User not found"]], Response::HTTP_NOT_FOUND);
        }
        if (!Hash::check($request->password, $user->password)) {
            return \response()->json(["errors" => ["Invalid credentials"]], Response::HTTP_FORBIDDEN);
        }

        // Auth::attempt([
        //     "email" => $request->email,
        //     "password" => $request->password
        // ]);

        // Auth::user()->tokens()->delete();

        // $id = Auth::id();
        // $token = Auth::user()->createToken('token')->plainTextToken;

        return \response()->json([
            "user_id" => $user->id,
        ]);
    }
    public function logout()
    {
        auth()->user()->tokens()->delete();


        return response()->json([
            'message' => 'Tokens Revoked'
        ], Response::HTTP_OK);
    }
}
