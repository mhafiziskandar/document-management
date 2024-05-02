<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'member']);

        $admin = User::create([
            'user_id' => 12321,
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'ic_no' => 'admin@email.com',
            'password' => bcrypt('password')
        ]);

        $admin->assignRole('admin');
    }
}
