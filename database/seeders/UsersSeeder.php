<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\Role;

class UsersSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run()
    {
        User::factory()->customUser('superadmin', 'superadmin@cmsisal.test', 'inipassword', Role::SUPERADMIN->value)->create();
        User::factory()->customUser('admin', 'admin@cmsisal.test', 'inipassword', Role::ADMIN->value)->create();
    }
}
