<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\PermissionManager;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Route;

class AuthorizationMiddleware
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
        // check if not authenticated!
        if (auth()->check() == false) {
            return response()->json([
                'success' => false,
                'message' => 'not_authenticated',
            ]);
        }

        // get route access, to check if have authorized to access resourcce or not
        $group       = $request->route()->getAction()['group'];
        $method      = $request->route()->getActionMethod();
        $permisssion = $group . '-' . $method;

        if ((new PermissionManager)->isUserCannot($permisssion)) {
            return response()->json([
                'success' => false,
                'message' => 'not_autherized_to_' . $permisssion,
            ]);
        }
        return $next($request);
    }
}
