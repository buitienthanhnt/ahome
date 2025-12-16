<?php
namespace App\Http\Controllers\Admin;

interface AdminUserControllerInterface{
    const CONTROLLER_NAME = 'Admin\AdminUserController';
    const PREFIX = 'adminUser';

    const LIST_ADMIN_USER = 'listAdminUser';
    const CREATE_ADMIN_USER = 'createAdminUser';
    const EDIT_ADMIN_USER = 'editAdminUser';
    const INSERT_ADMIN_USER = 'insertAdminUser';
    const UPDATE_ADMIN_USER = 'updateAdminUser';
    const DELETE_ADMIN_USER = 'deleteAdminUser';

    public function listAdminUser();

    public function createAdminUser();

    public function insertAdminUser();

    /**
     * @param int $admin_user_id
     * @return mixed
     */
    public function editAdminUser(int $admin_user_id);

    /**
     * @param int $admin_user_id
     * @return mixed
     */
    public function updateAdminUser(int $admin_user_id);

    public function deleteAdminUser();
}
