<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProject
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      
        $projectId = explode('/',$request->path())[1];
      
        $authenticatedUser = auth()->user();
        $projectAssigned = $authenticatedUser->projects->pluck('id')->toArray();
        if(in_array($projectId, $projectAssigned) || $authenticatedUser->hasRole('Admin')){
            return $next($request);
        };
        abort(403,'You do not have the permission to access this resource');
    }
}
