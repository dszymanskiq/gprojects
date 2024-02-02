<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class JoinProject
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user()?->type === 'student' && Session::has('project_id')) {
            $projectId = Session::get('project_id');
            Session::forget('project_id');

            return redirect()->route('student.projects.join',['project' => $projectId]);
        }
        return $next($request);
    }
}
