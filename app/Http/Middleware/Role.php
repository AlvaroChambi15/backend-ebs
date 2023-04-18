<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\AuthController;

class Role
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
        /*         if (!Auth::check()) {
            // return redirect('/login');
            return response()->json('El usuario no esta logeado');
        } */

        // $user = Auth::user();

        // Route::get('/perfil', [AuthController::class, "perfil"]);

        // $a = AuthController->perfil();


        $user = auth()->user();



        // $user = auth()->user();

        // $id = Auth::id();


        return response()->json($user);

        if (Auth::check()) {
        }




        /* if ($user->role == $role) {
            return $next($request);
        } else {
            return response()->json('El usuario no tiene permiso');
        } */
    }
}