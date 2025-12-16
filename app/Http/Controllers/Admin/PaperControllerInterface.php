<?php
namespace App\Http\Controllers\Admin;

interface PaperControllerInterface{
    const CONTROLLER_NAME = 'Admin\PaperController';
    const PREFIX = 'paper';

    const LIST_PAPER = 'listPaper';
    const CREATE_PAPER = 'createPaper';
    const INSERT_PAPER = 'insertPaper';
    const EDIT_PAPER = 'editPaper';
    const UPDATE_PAPER = 'updatePaper';
    const DELETE_PAPER = 'deletePaper';
    const NEW_BY_URL = 'newByUrl';
    const SOURCE_PAPER = 'sourcePaper';

    public function listPaper();
    public function createPaper();
    public function insertPaper();

    /**
     * @param int $paper_id
     * @return mixed
     */
    public function editPaper(int $paper_id);

    /**
     * @param int $paper_id
     * @return mixed
     */
    public function updatePaper(int $paper_id);

    public function deletePaper();

    public function newByUrl();

    public function sourcePaper();
}
