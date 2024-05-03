<?php

namespace Database\Seeders;

use App\Enums\Permission;
use App\Enums\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\Models\Role as SpatieRole;

class RolesAndPermissionsSeeder extends Seeder
{
    use WithoutModelEvents;

    protected Collection $roles;

    protected Collection $superAdminPermissions;

    protected Collection $orgnaisationPermissions;

    protected Collection $postPermissions;

    public function run()
    {
        $this->createRoles();
        $this->createPermissions();
        $this->assignPermissionsToRoles();
    }

    protected function createRoles()
    {
        $this->roles = \collect([
            Role::SUPERADMIN->value => SpatieRole::create(['name' => Role::SUPERADMIN]),
            Role::ADMIN->value => SpatieRole::create(['name' => Role::ADMIN]),
            Role::USER->value => SpatieRole::create(['name' => Role::USER]),
        ]);
    }

    protected function createPermissions()
    {
        //
    }

    protected function assignPermissionsToRoles()
    {
        //
    }
}
