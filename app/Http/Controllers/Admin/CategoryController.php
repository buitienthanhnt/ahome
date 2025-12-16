<?php

namespace App\Http\Controllers\Admin;

use App\Api\PaperFirebaseApi;
use App\Events\CacheClear;
use App\Helper\ImageUpload;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryInterface;
use App\Models\ConfigCategory;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use RealRashid\SweetAlert\Facades\Alert;

class CategoryController extends Controller implements CategoryControllerInterface
{
    use ImageUpload;

    protected $request;
    protected $category;
    protected $configCategory;
    protected $session;
    protected $paperFirebaseApi;

    public function __construct(
        Request $request,
        Category $category,
        ConfigCategory $configCategory,
        Store $session,
        PaperFirebaseApi $paperFirebaseApi
    ) {
        $this->request = $request;
        $this->category = $category;
        $this->configCategory = $configCategory;
        $this->session = $session;
        $this->paperFirebaseApi = $paperFirebaseApi;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function listCategory()
    {
        $all_category = $this->category::paginate(8); // php artisan vendor:publish --tag=laravel-pagination
        return view("adminhtml/templates/category/list", ["all_category" => $all_category]);
    }

    public function createCategory()
    {
        if ($this->session->exists("success")) {
            Alert::info($this->session->get("success"), 'Message')->autoClose(2000);
        } elseif ($this->session->exists("error")) {
            Alert::error($this->session->get("error"), 'Message')->autoClose(2000);
        }

        $parent_category = $this->category_tree_option();
        return view("adminhtml/templates/category/create", ["parent_category" => $parent_category]);
    }

    public function insertCategory()
    {
        $params = $this->request->toArray();
        if ($file = $this->request->__get("category_image")) {
            $image_upload_path = $this->uploadImage($file, "public/images/category", "images/resize/category");
        }

        $image_path = '';
        if (isset($image_upload_path) && $image_upload_path["file_path"]){
            $image_path = url($image_upload_path["file_path"]);
            if (false){
                $image_path = $this->paperFirebaseApi->upLoadImageFirebase(url($image_upload_path["file_path"]));
            }
        }

        $category = $this->category;
        $category->name = $params["name"];
        $category->description = $params["description"] ?: '';
        $category->active = $params["active"];
        $category->parent_id = $this->request->__get("parent_id");
        $category->url_alias = $params["url_alias"] ?: str_replace(" ", '-', strtolower($params['name']));
        $category->type = $this->request->get("type", 'default');
        $category->{CategoryInterface::ATTR_IMAGE_PATH} =  $image_path;
        $category->save();

        if ($category) {
            event(new CacheClear(['top_category', 'top_menu_view'])); // gọi vào event: CacheClear
            return back()->with("success", "created new category: $category->name");
        }
    }

    /**
     * @param int $category_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function editCategory($category_id)
    {
        $selected_category = $this->category::find($category_id);
        $category_tree_option = $this->category_tree_option($selected_category);

        return view("adminhtml/templates/category/edit", ["category" => $selected_category, "category_tree_option" => $category_tree_option]);
    }

    /**
     * @param int $category_id
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function updateCategory($category_id)
    {
        if ($file = $this->request->__get("image_path")) {
            $image_upload_path = $this->uploadImage($file, "public/images/category", "images/resize/category");
        }

        $category = $this->category::find($category_id);
        $request_params = $this->request->toArray();
        $request_params[CategoryInterface::ATTR_IMAGE_PATH] = isset($image_upload_path) && $image_upload_path["file_path"] ? url($image_upload_path["file_path"]) : null;
        $category->fill($request_params);
        $category->save();
        return redirect()->back()->with("success", "updated success!");
        // chuyển hướng qua trang danh sách category.
        // return redirect(route("category_admin_list"));
    }

    /**
     * hàm này trả về chuỗi các <option> của category cho trường <select> trong <form>.
     * @param Category $selected_category
     * @return string
     */
    protected function category_tree_option($selected_category = null)
    {
        $list_of_category = '<option value="0">Root category</option>';
        $list_catergory = $this->category->all()->where("parent_id", "=", 0);
        if ($list_catergory->count()) {
            if ($selected_category) {
                $list_of_category .= $this->category_tree($list_catergory, "", $selected_category->parent_id);
            } else {
                $list_of_category .= $this->category_tree($list_catergory);
            }
        }
        return $list_of_category;
    }

    /**
     * @param        $catergory
     * @param string $begin
     * @param Category|null   $selected
     * @return string
     */
    protected function category_tree($catergory, $begin = "", $selected = null)
    {
        $html = "";
        foreach ($catergory as $cate) {
            $html .= '<option value="' . $cate->id . '" ' . ($selected === $cate->id ? "selected" : "") . '>' . $begin . $cate->name . '</option>';
            if ($list_catergory = $this->category->all()->where("parent_id", "=", $cate->id)) {
                if ($list_catergory->count()) {
                    $_be = $begin;
                    $begin .= "___";
                    $html .= $this->category_tree($list_catergory, $begin, $selected);
                    $begin = $_be;
                } else {
                    continue;
                }
            }
        }
        return $html;
    }

    /**
     * @param int $category_id
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function deleteCategory($category_id)
    {
        $category = $this->category::find($category_id);
        if ($category) {
            $category->delete();
        }
        return back();
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function setupCategory()
    {
        $category_tree_option = $this->category_tree_option();
        $list_current = [];
        $current_setup = ConfigCategory::where("path", ConfigCategory::TOP_CATEGORY);
        if ($current_setup->count()) {
            $list_current = explode("&", $current_setup->first()->value);
        }
        $all_category = Category::all();
        $setup_type = [ConfigCategory::TOP_CATEGORY, ConfigCategory::CENTER_CATEGORY];
        return view("adminhtml/templates/category/setup",
            [
                "parent_category" => $category_tree_option,
                "all_category" => $all_category,
                "list_current" => $list_current,
                "setup_type" => $setup_type
            ]
        );
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setupSave()
    {
        $params = $this->request->toArray();
        $configCategory = $this->configCategory;
        $setup_type = $this->request->get("setup_type");
        if ($setup_type) {
            $top_category = $configCategory::firstWhere("path", $setup_type);
            if ($top_category) {
                $configCategory = $configCategory::find($top_category->id);
            }
        }
        $configCategory->fill([
            "path" => $setup_type ? str_replace(" ", "_", $setup_type) : ConfigCategory::TOP_CATEGORY,
            "value" => implode("&", $params["setup_category"]),
            "description" => $params['description'] ?? null
        ]);
        $configCategory->save();
        return redirect()->back();
    }
}
