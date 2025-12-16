<?php

namespace App\Models;

use App\Helper\Nan;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminUser extends Model implements AdminUserInterface
{
    use HasFactory;
    use SoftDeletes;
    use Nan;

    protected $guarded = [];
    protected $request;

    public function __construct(array $attributes = [])
    {
        $this->session_begin();
        parent::__construct($attributes);
    }

    /**
     * @param string $user_name
     * @param string $password
     * @return static|null
     */
    public function admin_login($user_name, $password)
    {
        if ($user_name && $password) {
            $admin_user  = $this->where(self::ATTR_NAME, $user_name)->first();
            if ($admin_user && $admin_user->{self::ATTR_ACTIVE} && Hash::check($password, $admin_user->password)) {
               return $admin_user;
            }
        }
        return null;
    }

    function admin_logout(): void
    {
        if ($admin_user = Session::get("admin_user")) {
            Session::remove("admin_user");
        }
    }

    public function get_admin_user(): mixed
    {
        return Session::get("admin_user", null);
    }

    public function check_login(): bool
    {
        return Session::has("admin_user");
    }

    public function session_begin(): void
    {
        if (!Session::isStarted()) {
            Session::start();
        }
    }

    /**
     * login by admin user
     *
     * @return bool
     */
    public function login_by_admin_user(): bool
    {
        try {
            if ($this->toArray()) {
                Session::push("admin_user", $this);
                Session::save();
                return true;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return false;
    }

    /**
     * @param AdminUser $admin_user
     * @param integer[] $permissions
     * @return bool
     * @throws Exception
     */
    public function savePermissions($admin_user, $permissions)
    {
        if ($admin_user && $permissions) {
            $userPermission = $admin_user->hasMany(AdminUserPermission::class, AdminUserInterface::PRIMARY_ALIAS)->getResults();
            /**
             * delete old permission
             */
            collect($userPermission)->map(fn($item) => $item->delete());
            DB::beginTransaction();
            try {
                foreach ($permissions as $permission) {
                    DB::table($this->userPermissionTable())->updateOrInsert([
                        AdminUserInterface::PRIMARY_ALIAS => $admin_user->id,
                        PermissionInterface::PRIMARY_ALIAS => $permission
                    ]);
                }
                DB::commit();
                return true;
            } catch (Exception $e) {
                DB::rollBack();
                throw new Exception($e->getMessage());
            }
        }
    }

    /**
     * get germissions
     */
    function getPermissionsIds()
    {
        $permissions = $this->hasMany(AdminUserPermission::class, AdminUser::PRIMARY_ALIAS);
        $permissionValues = array_column($permissions->getResults()->toArray(), "permission_id");
        return $permissionValues;
    }

    function getPermissionRules()
    {
        $rules = [];
        $userPermissions = array_column($this->hasMany(AdminUserPermission::class, AdminUserInterface::PRIMARY_ALIAS)->getResults()->toArray(), 'permission_id');
        $permission = Permission::where('label', '=', 'root')->first()->id ?? null;
        if (in_array($permission, $userPermissions)) {
            return ['rootAdmin'];
        } else {
            $userPermissions = collect($this->hasMany(AdminUserPermission::class, AdminUser::PRIMARY_ALIAS)->getResults());
            foreach ($userPermissions->all() as $userPermission) {
                $data_rules = $userPermission->hasMany(PermissionRules::class, "permission_id", "permission_id")->getResults()->map(fn($item) => $item->rule_value)->all();
                $rules = [...$rules, ...$data_rules];
            }
        }
        return $rules;
    }

    public function __destruct()
    {
    }
}
