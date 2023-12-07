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




        Permission::create(['name' => 'view-product']);
        Permission::create(['name' => 'create-product']);
        Permission::create(['name' => 'delete-product']);
        Permission::create(['name' => 'update-product']);
        // customer
        Permission::create(['name' => 'login-app']);
        Permission::create(['name' => 'order']);
        Permission::create(['name' => 'review']);
        Permission::create(['name' => 'complaint']);

        // shipper & ke toan
        Permission::create(['name' => 'change-state-order']);


        /**
         * Quyền cho Đơn hàng
         *
         */
        Permission::create(['name' => 'order-pending']);
        Permission::create(['name' => 'order-packing']);
        Permission::create(['name' => 'order-shipping']);
        Permission::create(['name' => 'order-completed']);
        Permission::create(['name' => 'order-refund']);
        Permission::create(['name' => 'order-decline']);


        Permission::create(['name' => 'shipper']);
        Permission::create(['name' => 'saler']);

        Permission::create(['name' => 'contract-pending']);
        Permission::create(['name' => 'contract-create']);
        Permission::create(['name' => 'contract-cancle']);
        Permission::create(['name' => 'contract-complete']);
        Permission::create(['name' => 'create-contract-complete']);

        Permission::create(['name' => 'add-new-package']);

        Permission::create(['name' => 'viewer-custommer']);
        Permission::create(['name' => 'info-customer']);
        Permission::create(['name' => 'package-custommer']);
        Permission::create(['name' => 'document-custommer']);

        Permission::create(['name' => 'view-shipper']);
        Permission::create(['name' => 'create-schedule']);
        Permission::create(['name' => 'add-order-shipper']);


        Permission::create(['name' => 'view-news']);
        Permission::create(['name' => 'create-news']);
        Permission::create(['name' => 'update-news']);
        Permission::create(['name' => 'delete-news']);


        Permission::create(['name' => 'view-setting']);
        Permission::create(['name' => 'view-notification']);
        Permission::create(['name' => 'view-setting-privacy']);
        Permission::create(['name' => 'view-setting-contact']);
    }
}
