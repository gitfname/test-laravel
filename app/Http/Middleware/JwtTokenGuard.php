<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Firebase\JWT\Key;
use Firebase\JWT\JWT;

class JwtTokenGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tokenArray = explode(" ", $request->header("Authorization"));
        $token = "";

        if(count($tokenArray) != 2) {
            return response([
                "message" => "Invalid or missing token"
            ], 400);
        }

        $token = $tokenArray[1];

        if(!$token) {
            return response([
                "message" => "Invalid or missing token"
            ], 400);
        }

        try {
            $decoded = JWT::decode($token, new Key("this is a sooooo secure jwt password", "HS256"));
            
            $request->attributes->set("verified-jwt-payload", $decoded->data);
        }
        catch(\UnexpectedValueException $exp) {
            return response([
                "message" => "Invalid token"
            ], 400);
        }
        catch(Exception $exp) {
            return response([
                "message" => "Invalid token"
            ], 400);
        }

        return $next($request);
    }
}
