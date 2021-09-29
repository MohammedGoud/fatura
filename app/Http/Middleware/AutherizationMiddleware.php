<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\PermissionManager;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Route;

class AutherizationMiddleware
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

        if (auth()->check() == false) {
            return response()->json([
                'success' => false,
                'message' => 'not_authenticated',
            ]);
        }

        $group       = $request->route()->getAction()['group'];
        $method      = $request->route()->getActionMethod();
        $permisssion = $method . '-' . $group;

        if ((new PermissionManager)->isUserCannot($permisssion)) {
            return response()->json([
                'success' => false,
                'message' => 'not_autherized_to_' . $permisssion,
            ]);
        }
        return $next($request);
    }
}
