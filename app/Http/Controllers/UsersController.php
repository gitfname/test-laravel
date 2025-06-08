<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\AppUser;
use App\Http\Resources\UserResource;

class UsersController extends Controller
{
    private $jwtSecret = "this is a sooooo secure jwt password";

    
    public function login(Request $req) {
        $validated = $req->validate([
            "username" => "required|max:50",
            "password" => "required|min:4|max:14"
        ]);

        $user = AppUser::where("username", $validated["username"])->first();

        if(!$user || !password_verify($validated["password"], $user->password)) {
            return response()->json([
                "message" => "wrong credentials"
            ], 400);
        }

        $payload = [
            "exp" => time() + 3600,
            "data" => [
                "id" => $user->id
            ]
        ];

        return response()->json([
            "token" => JWT::encode($payload, $this->jwtSecret, "HS256")
        ]);
    }

    public function signup(Request $req) {
        $validated = $req->validate([
            "username" => "required|max:50|unique:app_users,username",
            "email" => "required|unique:app_users,email|max:100",
            "phone_number" => "required|min:11|max:11|unique:app_users,phone_number",
            "password" => "required|min:4|max:14|confirmed",
        ]);

        $data = $validated;
        $data["password"] = password_hash($validated["password"], PASSWORD_BCRYPT);
        $data["is_phone_verified"] = false;

        AppUser::create($data);
    }

    public function getMyProfile(Request $req) {
        $userId = $req->attributes->get("verified-jwt-payload")->id;
        $user = AppUser::where("id", $userId)->first();

        if(!$user) {
            return response()->json([
                "message" => "user not found"
            ], 404);
        }

        return new UserResource($user);
    }

    public function findAllUsers(Request $req) {
        $users = AppUser::all();
        return UserResource::collection($users);
    }
}
