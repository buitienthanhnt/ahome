<?php
namespace App\Http\Controllers\Admin;

interface FirebaseControllerInterface{
    const CONTROLLER_NAME = 'Admin\FirebaseController';
    const PREFIX = 'firebase';

    const DASH_BOARD = 'dashboard';
    const FIREBASE_UPLOAD_PAPER = 'firebaseUploadPaper';
    const FIREBASE_UPLOAD_WRITER = 'firebaseUploadWriter';
    const FIREBASE_DELETE_PAPER =  'firebaseDeletePaper';
    const FIREBASE_SETUP_HOME_INFO = 'firebaseSetupHomeInfo';
    const FIREBASE_UPLOAD_HOME_INFO = 'firebaseUploadHomeInfo';
    const FIREBASE_UPLOAD_CATEGORY_TOP = 'firebaseUploadCategoryTop';
    const FIREBASE_UPLOAD_CATEGORY_TREE = 'firebaseUploadCategoryTree';

    public function dashboard();

    public function firebaseUploadPaper();

    public function firebaseDeletePaper();

    public function firebaseSetupHomeInfo();

    public function firebaseUploadHomeInfo();

    public function firebaseUploadCategoryTop();

    public function firebaseUploadCategoryTree();

    public function firebaseUploadWriter();
}
