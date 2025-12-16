<?php

namespace App\Http\Controllers\Frontend;

interface ExtensionControllerInterface{
    const CONTROLLER_NAME = 'Frontend\ExtensionController';

    const HOME_INFO = 'homeInfo';

    const SEARCH = 'search';
    const PAPER_MOST_VIEW = 'getPaperMostView';
    const LOGIN = 'login';
    const USER_INFO = 'getUserInfo';
    const REGISTER = 'registerUser';

    public function search();

    public function getPaperMostView();

    public function login();

    public function getUserInfo();

    public function registerUser();
}
