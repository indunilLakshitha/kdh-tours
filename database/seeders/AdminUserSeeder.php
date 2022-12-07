<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        $admin_user['username'] = 'Super Admin';
        $admin_user['email'] = 'suadmin@bizeyes.com';
        $admin_user['password'] = Hash::make('Abcd@1234');




        $user = AdminUser::where('email',  $admin_user['email'])->first();
        if (!$user) {
            $user = AdminUser::create($admin_user);
        }
    }
}
