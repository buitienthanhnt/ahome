<?php

namespace App\Services;

use Kreait\Firebase;
use Kreait\Firebase\Factory;

// tài liệu php firebase.
// https://firebase-php.readthedocs.io/en/stable/
// https://github.com/kreait/laravel-firebase#usage

// nên chọn: firebase-adminsdk(gốc) trong list service để tạo key json.
// https://console.cloud.google.com/iam-admin/serviceaccounts/details/111481399996955463387/keys?project=react-cli4&supportedpurview=project :cli4
// https://console.cloud.google.com/iam-admin/serviceaccounts/details/109858435023888922380/keys?project=cli6-c4945&supportedpurview=project :cli6

class FirebaseService
{

    //    const CONNECT_FIREBASE_PROJECT = 'firebase-cli4';
    //   const FIREBASE_DATABASE_URL = 'https://react-cli4-default-rtdb.firebaseio.com/';

    const CONNECT_FIREBASE_PROJECT = 'firebase-newpaper';
    const FIREBASE_DATABASE_URL = 'https://newpaper-25148-default-rtdb.firebaseio.com/';

    /**
     * @var Firebase
     */
    public $firebase;
    public $fireStore;
    public $remoteConfig;
    public $messagesing;

    public function __construct()
    {
        $path = storage_path("app/".self::CONNECT_FIREBASE_PROJECT."/firebaseConfig.json");
        $this->firebase = (new Factory)->withServiceAccount($path)->withDatabaseUri(env('FIREBASE_DATABASE_URL', self::FIREBASE_DATABASE_URL));
        // $this->fireStore = (new Factory)->withServiceAccount($path)->createFirestore()->database();
        $this->remoteConfig = (new Factory)->withServiceAccount($path)->createRemoteConfig();
        $this->messagesing = (new Factory)->withServiceAccount($path)->createMessaging();
    }
}
