<?php
namespace App\Http\Controllers\Admin;

interface PermissionControllerInterface{
    const CONTROLLER_NAME = 'Admin\PermissionController';
    const PREFIX = 'permission';

    const LIST_PERMISSION = 'listPermission';
    const CREATE_PREMISSION = 'createPermission';
    const INSERT_PERMISSION = 'insertPermission';
    const EDIT_PERMISSION = 'editPermission';
    const UPDATE_PERMISSION = 'updatePermission';
    const DELETE_PERMISSION = 'deletePermission';
    const DETAIL_PERMISSION = 'detailPermission';

    public function listPermission();

    public function createPermission();

    public function insertPermission();

    /**
     * @param int $permission_id
     * @return mixed
     */
    public function editPermission(int $permission_id);

    /**
     * @param int $permission_id
     * @return mixed
     */
    public function updatePermission(int $permission_id);

    /**
     * @return mixed
     */
    public function deletePermission();

    /**
     * @param int $permission_id
     * @return mixed
     */
    public function detailPermission(int $permission_id);
}
