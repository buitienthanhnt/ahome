<?php
namespace App\Http\Controllers\Admin;

interface WriterControllerInterface{
    const CONTROLLER_NAME = 'Admin\WriterController';
    const PREFIX = 'writer';

    const LIST_WRITER = 'listWriter';
    const CREATE_WRITER = 'createWriter';
    const INSERT_WRITER = 'insertWriter';
    const EDIT_WRITER = 'editWriter';
    const UPDATE_WRITER = 'updateWriter';
    const DELETE_WRITER = 'deleteWriter';

    public function listWriter();

    public function createWriter();

    public function insertWriter();

    /**
     * @param integer $writer_id
     * @return mixed
     */
    public function editWriter($writer_id);

    /**
     * @param integer $writer_id
     * @return mixed
     */
    public function updateWriter($writer_id);

    public function deleteWriter();
}
