<?php
namespace App\Models;

interface NotificationInterface{
    const TABLE_NAME = 'notifications';

    const ATTR_TYPE = 'type';
    const ATTR_FCM_TOKEN = 'fcmToken';
    const ATTR_DEVICE_ID = 'deviceId';
    const ATTR_ACTIVE = 'active';
}
