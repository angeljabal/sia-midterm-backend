<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $movie = $request->route('movie');
        
        if($movie==null){
            return response()->json(['message'=>'The movie cannot be found'], 404);
        }
        
        if($movie->user_id != auth()->user()->id){
            return response()->json(['message'=>'You are not the owner of this.'], 401);
        }
        return $next($request);
    }
}
