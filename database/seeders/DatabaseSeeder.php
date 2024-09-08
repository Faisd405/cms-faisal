<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\Dump;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UsersSeeder::class,
            DumpSeeder::class,
        ]);
    }
}
