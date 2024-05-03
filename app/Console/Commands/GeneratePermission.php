<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\PermissionHelper;
use Illuminate\Console\Command;

class GeneratePermission extends Command
{
    use PermissionHelper;

    /**
     * The name and signature of the console command.
     * Parameter:
     * 1. remove unused permission at database if doesn't exist in config/app_config/app_permission
     *
     * @var string
     */
    protected $signature = 'faisaldev:generate-permission
                            {--remove-unused-permission : Remove unused permission at database if doesn\'t exist in config/app_config/app_permission}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate permission for module user and role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating permission...');

        $this->generatePermission();

        if ($this->option('remove-unused-permission')) {
            $this->removeUnusedModule();
            $this->removeUnusedPermission();
        }

        $this->info('Permission generated successfully.');
    }


}
