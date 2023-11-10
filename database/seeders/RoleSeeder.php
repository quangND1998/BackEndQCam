<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo([
            'super-admin',
            'update-user',
            'delete-user',
            'create-user',
            'view-user',
            'view-land',
            'create-land',
            'update-land',
            'delete-land',
            'view-product',
            'create-product',
            'update-product',
            'delete-product'
        ]);
        $role->givePermissionTo(['super-admin', 'update-user', 'delete-user', 'create-user', 'view-user', 'view-land',  'create-land',  'update-land', 'delete-land']);

        $role2 = Role::create(['name' => 'customer']);
        $role2->givePermissionTo(['login-app', 'order', 'review', 'complaint']);
    }
}
