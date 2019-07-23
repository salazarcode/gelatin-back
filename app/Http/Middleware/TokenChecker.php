<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Crypt;
use App\User;

use Closure;

class TokenChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('token');
        $user = User::where("session_token", $token)->first();
        if($user != null)
            return $next($request);
        else    
            return response()->json(array(
                "success" => 0,
                "message" => "Fuck off bitch. Private Only"
            ));
    }
}
