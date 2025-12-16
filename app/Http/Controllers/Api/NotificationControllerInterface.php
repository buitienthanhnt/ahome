<?php
namespace App\Http\Controllers\Api;

interface NotificationControllerInterface{
    const CONTROLLER_NAME = 'api\NotificationController';
    const PREFIX = 'notification';
    const REGISTER_FCM = 'registerFcm';
    const PUSH_NOTIFICATION = 'pushNotification';

    public function registerFcm();

    public function pushNotification();
}
