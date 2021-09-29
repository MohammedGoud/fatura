<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Services\PermissionManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function lists()
    {
        // return lists of user roles
        return Role::where(['user_id' => Auth::id()])->get();

    }

    public function list_system_permissions()
    {
        $permissions = (new PermissionManager)->getSystemPermissions();

        return response()->json([
            'success' => true,
            'message' => 'Permission Reterived successfully',
            'data'    => $permissions,
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        //Validate data
        $data      = $request->only('name', 'slug', 'permissions');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'slug' => 'required|string|unique:roles',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new role
        $role = Role::create([
            'name'        => $request->name,
            'slug'        => $request->slug,
            'user_id'     => Auth::id(),
            'permissions' => json_encode($request->permissions),
        ]);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Role created successfully',
            'data'    => $role,
        ], Response::HTTP_OK);
    }
}
