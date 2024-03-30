<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleHasPermission;
use App\Models\UserHasRole;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    static function assignRole($user, $role)
    {
        $roleId = Role::where('name', $role)->pluck('id');
        UserHasRole::create([
            'user_id' => $user,
            'role_id' => $roleId[0]
        ]);
    }

    static function givePermissionsToRole($role, $permissions)
    {
        $role_id = Role::where('name', $role)->get()->first();
        $permissions_id = RoleHasPermission::where('role_id', $role_id->id)->pluck('permission_id');
        foreach ($permissions_id as $permission) {
            RoleHasPermission::create([
                'role_id' => $role_id,
                'permission_id' => $permission
            ]);
        }
    }

    static function userCan($user_id, $permissions)
    {
        $userRole = UserHasRole::where('user_id', $user_id)->first();

        if (!$userRole) {
            return response()->json([
                'error' => 'User role not found'
            ], 404);
        }

        $rolePermissions = RoleHasPermission::where('role_id', $userRole->role_id)->pluck('permission_id');

        if ($rolePermissions->isEmpty()) {
            return response()->json([
                'error' => 'Role permissions not found'
            ], 404);
        }

        $permissionsIds = Permission::whereIn('name', $permissions)->pluck('id')->toArray();

        foreach ($permissionsIds as $permission) {
            if (!$rolePermissions->contains($permission)) {
                return false;
            }
        }

        return true;
    }


    static function userHasRole($user_id, $role)
    {
        $userRole = UserHasRole::where('user_id', $user_id)->get()->first();

        if (!$userRole) {
            return response()->json([
                'error' => 'User role not found'
            ], 404);
        }

        $role_id = Role::where('name', $role)->value('id');

        if (!$role_id) {
            return response()->json([
                'error' => 'Role not found'
            ], 404);
        }

        if ($userRole->role_id !== $role_id || !$userRole) {
            return false;
        }

        return true;
    }

    function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3'
        ]);

        Permission::create([
            'name' => $request->permission
        ]);
        return response()->json([
            'status' => 'success'
        ]);
    }

    function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3'
        ]);

        Role::create([
            'name' => $request->permission
        ]);
        return response()->json([
            'status' => 'success'
        ]);
    }

}
