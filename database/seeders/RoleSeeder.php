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
            'delete-product',
            // order permission
            'order-pending', 'order-packing', 'order-shipping', 'order-completed', 'order-refund', 'order-decline','order','complaint','change-state-order',
            'contract-pending',   'contract-create',   'contract-cancle',   'contract-complete',   'create-contract-complete',   'add-new-package',   'view-news',
            'create-news', 'update-news', 'delete-news'
        ]);
       

        $customer = Role::create(['name' => 'customer']);
        $customer->givePermissionTo(['login-app', 'order', 'review', 'complaint']);

        $shipper = Role::create(['name' => 'shipper']);
        $shipper->givePermissionTo(['shipper']);

        $leader_sale = Role::create(['name' => 'leader-sale']);
        $leader_sale->givePermissionTo([
            'update-user',
            'delete-user',
            'create-user',
            'view-user',
            'order-pending', 'order-packing', 'order-shipping', 'order-completed', 'order-refund', 'order-decline'
        ]);

        $leader_shipper = Role::create(['name' => 'leader-sale']);
        $leader_shipper->givePermissionTo([
            'update-user',
            'delete-user',
            'create-user',
            'view-user',
            'view-shipper', 'add-order-shipper'
        ]);

        $saler = Role::create(['name' => 'saler']);
        $saler->givePermissionTo(['saler',
            'order-pending', 'order-packing', 'order-shipping', 'order-completed', 'order-refund', 'order-decline','add-new-package',
            'contract-pending',    'contract-cancle',   'contract-complete', 
        ]);


        $Ketoan = Role::create(['name' => 'Kế toán']);
        $Ketoan->givePermissionTo(['saler',
            'order-pending', 'order-packing', 'order-shipping', 'order-completed', 'order-refund', 'order-decline','create-contract-complete',   'add-new-package', 
            'contract-pending','contract-create',  'contract-cancle',   'contract-complete', 
        ]);



        $shopmanager = Role::create(['name' => 'shopmanager']);
        $shopmanager->givePermissionTo(['saler',
            'view-product', 'create-product', 'update-product',  'add-new-package'
        ]);

        $cskh = Role::create(['name' => 'cskh']);
        $cskh->givePermissionTo(['saler','document-custommer', 'package-custommer', 'info-customer',  'viewer-custommer']);

        $event = Role::create(['name' => 'event']);
        $event->givePermissionTo(['order','complaint','change-state-order','order-pending', 'order-packing', 'order-shipping', 'order-completed', 'order-refund', 'order-decline','saler',
        'contract-pending','contract-create',  'contract-cancle',   'contract-complete']);


        $quanlytintuc = Role::create(['name' => 'quản lý tin tức']);
        $quanlytintuc->givePermissionTo([ 'view-news',
        'create-news', 'update-news', 'delete-news']);

        $thongtinchung = Role::create(['name' => 'thông tin chung']);
        $thongtinchung->givePermissionTo([ 'view-setting',
        'view-notification', 'view-setting-privacy', 'view-setting-contact']);


        $ctv = Role::create(['name' => 'ctv']);
        $ctv->givePermissionTo([ 'view-news']);

        $telesale = Role::create(['name' => 'telesale']);
        $telesale->givePermissionTo([ 'view-news']);
    }
}
