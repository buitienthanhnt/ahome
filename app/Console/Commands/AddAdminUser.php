<?php

namespace App\Console\Commands;

use App\Models\AdminUser;
use App\Models\AdminUserInterface;
use App\Models\Permission;
use App\Models\PermissionInterface;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * https://viblo.asia/p/create-a-custom-artisan-command-laravel-55-6J3Zg2QAKmB
 */
class AddAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * php artisan tha:addAdminUser admin admin@gmail.com admin123
     *
     * @var string
     */
    protected $signature = 'tha:addAdminUser {admin_user} {admin_email} {admin_password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add new admin user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $rootAdmin = Permission::where(PermissionInterface::ATTR_LABEL, PermissionInterface::PERMISSION_ROOT)->first();
            if (!$rootAdmin) {
                // insert root permission by const value.
                $rootAdmin = new Permission(
                    [
                        PermissionInterface::ATTR_LABEL => PermissionInterface::PERMISSION_ROOT,
                        PermissionInterface::ATTR_KEY => PermissionInterface::PERMISSION_ROOT
                    ]
                );
                $rootAdmin->save();
            }
            $adminUser = new AdminUser(
                [
                    AdminUserInterface::ATTR_NAME => $this->argument("admin_user"),
                    AdminUserInterface::ATTR_EMAIL => $this->argument("admin_email"),
                    AdminUserInterface::ATTR_PASSWORD => Hash::make($this->argument("admin_password")),
                    AdminUserInterface::ATTR_ACTIVE => AdminUserInterface::ACTIVE_VALUE,
                    AdminUserInterface::ATTR_CREATED_AT => (string)Carbon::now()->getTimestamp(),
                    AdminUserInterface::ATTR_UPDATED_AT => (string)Carbon::now()->getTimestamp()
                ]
            );
            $adminUser->save();
            $adminUser->savePermissions($adminUser, [$rootAdmin->id]);
            $this->info("add new adminUser success!");
        } catch (\Throwable $th) {
            $this->error("add new adminUser fail! message: " . $th->getMessage());
        }
    }

    protected function insertByBD()
    {
        try {
            DB::beginTransaction();
            //update publish_time column
            DB::table(AdminUserInterface::TABLE_NAME)->update([
                AdminUserInterface::ATTR_NAME => $this->argument("admin_user"),
                AdminUserInterface::ATTR_EMAIL => $this->argument("admin_email"),
                AdminUserInterface::ATTR_PASSWORD => Hash::make($this->argument("admin_password")),
                AdminUserInterface::ATTR_ACTIVE => AdminUserInterface::ACTIVE_VALUE
            ]);
            DB::commit();

            //send output to the console
            $this->info('Success!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }
    }
}
