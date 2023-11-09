<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $isExist = User::select("*")
            ->where("email", "admin@admin.com")
            ->exists();

        if (!$isExist) {
            $user1 = User::create([
                'name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@admin.com',
                'phone_number' => '0358794449',
                'password' => bcrypt('Abcd1234')
            ]);
            $user1->assignRole('super-admin');

            $user2 = User::create([
                'name' => 'Admin',
                'username' => 'quangnd620',
                'email' => 'quangnd620@wru.vn',
                'phone_number' => '0989227252',
                'password' => bcrypt('Abcd1234')
            ]);
        }
    }
}
