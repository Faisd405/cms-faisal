<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\PermissionHelper;
use Illuminate\Console\Command;

class RemoveUnusedPermission extends Command
{
    use PermissionHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'faisaldev:remove-unused-permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove unused permission at database if doesn\'t exist in config/app_config/app_permission';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Removing unused permission...');

        $this->removeUnusedModule();
        $this->removeUnusedPermission();

        $this->info('Unused permission removed successfully.');
    }
}
