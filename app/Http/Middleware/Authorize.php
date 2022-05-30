<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class Authorize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {

        $token = request()->header('Authorization');
        $dbToken = explode(' ', $token);
        $user = User::where('api_token', $dbToken[1])->first();
        if($user->role == $role){
            return $next($request);
        }
        $data = [
            'status' => false,
            'message' => 'Access denied',
            'date' => null
        ];
        return response()->json($data);
    }
}
