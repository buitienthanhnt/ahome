<?php
namespace App\Http\Controllers\Admin;

interface CategoryControllerInterface{
    const CONTROLLER_NAME = 'Admin\CategoryController';
    const PREFIX = 'category';

    const LISTCATEGORY = 'listCategory';
    const CREATE_CATEGORY = 'createCategory';
    const INSERT_CATEGORY = 'insertCategory';
    const EDIT_CATEGORY = 'editCategory';
    const UPDATE_CATEGORY = 'updateCategory';
    const DELETE_CATEGORY = 'deleteCategory';
    const SETUP_CATEGORY = 'setupCategory';
    const SETUP_SAVE = 'setupSave';

    public function listCategory();

    public function createCategory();

    public function insertCategory();

    /**
     * @param int $category_id
     * @return mixed
     */
    public function editCategory(int $category_id);

    /**
     * @param int $category_id
     * @return mixed
     */
    public function updateCategory(int $category_id);

    /**
     * @param int $category_id
     * @return mixed
     */
    public function deleteCategory(int $category_id);

    public function setupCategory();

    public function setupSave();
}

?>
