<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use closure;
use App\Models\User;

class Authenticate extends Middleware
{

    /**
     * Check an incoming request for authorization.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (request()->header('Authorization'))
        {
            $token = request()->header('Authorization');
            $dbToken = explode(' ', $token);
            if (User::where('api_token', $dbToken[1])->first()){

                return $next($request);
            }
        }
        $data = [
            'status' => false,
            'message' => 'Login Required',
            'date' => request()->header('Authorization')
        ];
        return response()->json($data);
    }
}
