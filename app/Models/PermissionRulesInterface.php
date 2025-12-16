<?php
namespace App\Models;
interface PermissionRulesInterface{
    const TABLE_NAME = 'permission_rules';

    const ATTR_PERMISSION_ID = PermissionInterface::PRIMARY_ALIAS;
    const ATTR_VALUE = 'rule_value';
}
