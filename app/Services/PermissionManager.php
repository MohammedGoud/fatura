<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;

class PermissionManager
{
    /**
     * Check if the given user Can Access the given Route.
     *
     * @param array $route
     *
     * @return bool
     */
    public function isUserCan($route): bool
    {
        if (auth()->check() == false) {
            return false;
        }

        $userPermissions = $this->getUserPermissions();

        return in_array($route, $userPermissions);
    }

    /**
     * Check if the Logged in user can not access the given route.
     *
     * @param $route
     *
     * @return bool
     */
    public function isUserCannot($route): bool
    {
        return !$this->isUserCan($route);
    }

    /**
     * Load all available System Permissions Grouped by group name.
     *
     * @return array
     */
    public function getSystemPermissions(): array
    {
        $systemRoutes = Route::getRoutes()->getRoutes();

        $permissions = [];

        foreach ($systemRoutes as $route) {

            if (isset($route->getAction()['group'])) {
                $group         = $route->getAction()['group'];
                $method        = $route->getActionMethod();
                $permissions[] = $group . '-' . $method;
            }
        }

        return $permissions;
    }

    /**
     * Get All Permissions For Current Logged in user.
     *
     * @return array
     */
    public function getUserPermissions()
    {
        if (auth()->check() == false) {
            return [];
        }

        $user = auth()->user();
        // All permissions and Roles
        if ($user->role->permissions) {
            $permission_roles = array_merge(json_decode($user->role->permissions), json_decode($user->permissions));
            return $permission_roles ?? [];
        }
        return json_decode($user->permissions) ?? [];

    }
}
