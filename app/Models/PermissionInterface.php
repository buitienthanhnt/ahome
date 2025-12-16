<?php
namespace App\Models;

interface PermissionInterface{
    const TABLE_NAME = 'permissions';

    const ATTR_LABEL = 'label';
    const ATTR_ACTIVE = 'active';
    const ATTR_KEY = 'key';

    const PRIMARY_ALIAS = 'permission_id';

    const PERMISSION_ROOT = 'root';
}
