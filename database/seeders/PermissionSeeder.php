<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('cache:clear');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::create(['name' => 'super-admin']);

        /**
         *  user
         */
        Permission::create(['name' => 'view-user']);
        Permission::create(['name' => 'create-user']);
        Permission::create(['name' => 'delete-user']);
        Permission::create(['name' => 'update-user']);

        Permission::create(['name' => 'view-land']);
        Permission::create(['name' => 'create-land']);
        Permission::create(['name' => 'delete-land']);
        Permission::create(['name' => 'update-land']);

        // customer
        Permission::create(['name' => 'login-app']);
        Permission::create(['name' => 'order']);
        Permission::create(['name' => 'review']);
        Permission::create(['name' => 'complaint']);

        // shipper & ke toan
        Permission::create(['name' => 'change-state-order']);

    }
}
