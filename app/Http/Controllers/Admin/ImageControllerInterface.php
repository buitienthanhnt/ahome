<?php
namespace App\Http\Controllers\Admin;

interface ImageControllerInterface{
    const CONTROLLER_NAME = 'Admin\ImageController';
    const PREFIX = 'file';

    const LIST_FILE = 'listFile';
    const ADD_FILE = 'addFile';
    const SAVE_FILE = 'saveFile';
    const DELETE_FILE = 'deleteFile';

    public function listFile();

    public function addFile();

    public function saveFile();

    public function deleteFile();
}
