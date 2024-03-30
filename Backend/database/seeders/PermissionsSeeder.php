<?php

namespace Database\Seeders;

use App\Http\Controllers\RoleController;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private $roles = [
        'admin',
        'user'
    ];

    private $permissions = [
        'role-list',
        'role-create',
        'role-edit',
        'role-delete',
        'update-user-role',
        'create-user',
        'update-user',
        'list-user',
        'delete-user'
    ];

    public function run(): void
    {
        foreach ($this->permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        foreach ($this->roles as $role) {
            Role::create(['name' => $role]);
        }

        RoleController::givePermissionsToRole('admin', $this->permissions);
    }
}
