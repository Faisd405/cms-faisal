<?php

namespace App\Console\Commands\Traits;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

// WIP - This is a work in progress
// 1. Change Path Permission Data from config to enum
trait PermissionHelper
{
    // =========================== Generate Permission =========================
    private function generatePermission()
    {
        $this->info('Generating permission for module user and role...');

        $files = $this->getFilePermissionPath();

        // loop all file
        foreach ($files as $file) {
            $this->processFileForGenerating($file);
        }

        $this->info('Permission generated successfully.');
    }

    private function processFileForGenerating($file)
    {
        $fileName = basename($file, '.php');
        $fileContent = require $file;

        foreach ($fileContent as $content) {
            $this->createPermissionIfNeeded($content['name'], $fileName);
        }
    }

    private function createPermissionIfNeeded($name, $module)
    {
        if (!Permission::where('name', $name)->where('module', $module)->exists()) {
            Permission::create([
                'name' => $name,
                'module' => $module,
            ]);
        }
    }

    // =========================== Remove Unused Permission =========================
    private function removeUnusedPermission()
    {
        $this->info('Removing unused permission...');

        $files = $this->getFilePermissionPath();

        // loop all file
        foreach ($files as $file) {
            $this->processFileForRemovingPermission($file);
        }

        $this->info('Unused permission removed successfully.');
    }

    private function processFileForRemovingPermission($file)
    {
        $fileName = basename($file, '.php');
        $fileContent = require $file;

        $permissions = Permission::where('module', $fileName)->pluck('name')->toArray();

        foreach ($permissions as $permission) {
            if (!in_array($permission, array_column($fileContent, 'name'))) {
                Permission::where('name', $permission)->delete();
            }
        }
    }

    // =========================== Remove Unused Module =========================
    private function removeUnusedModule()
    {
        $this->info('Removing unused module...');

        $files = $this->getFilePermissionPath();

        $moduleNames = array_map(fn($file) => basename($file, '.php'), $files);

        Permission::whereNotIn('module', $moduleNames)->delete();

        $this->info('Unused module removed successfully.');
    }

    // =========================== Clear All Permission =========================
    private function clearAllPermission()
    {
        $this->info('Clearing all permission...');

        Permission::truncate();

        $this->info('All permission cleared successfully.');
    }

    // =========================== Helper =========================
    public function getFilePermissionPath()
    {
        return glob(config_path('app_config/app_permission/*.php'));
    }
}
