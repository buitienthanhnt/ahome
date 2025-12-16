<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ImageUpload;
use App\Http\Controllers\Controller;
use App\Models\ImageModel;
use Illuminate\Http\Request;

class ImageController extends Controller implements ImageControllerInterface
{
    //
    use ImageUpload;

    protected $request;

    public function __construct(
        Request $request
    )
    {
        $this->request = $request;
    }

    public function listFile()
    {
        $list_file = ImageModel::paginate(8);
        return view("adminhtml.templates.files.list", compact("list_file"));
    }

    public function addFile()
    {
        return view("adminhtml.templates.files.add");
    }

    public function deleteFile()
    {
        $result = false;
        $request = $this->request;
        $params = $request->json()->all();
        if (isset($params["file_path"]) && $file_path = $params["file_path"]) {
            $result = $this->delete_real_file($file_path);
        }

        if (isset($params["resize_path"]) && $resize_path = $params["resize_path"]) {
            $result = $this->delete_real_file($resize_path);
        }

        if (isset($params["id"]) && $id = $params["id"]) {
            ImageModel::find($id)->delete();
        }
        if ($result) {
            return response(json_encode([
                "code" => "200",
                "value" => "deleted: success"
            ]), 200);
        }else {
            return response(json_encode([
                "code" => "401",
                "value" => "deleted: error"
            ]), 401);
        }
    }

    public function saveFile()
    {

        $request = $this->request;
        $save_image = $request->__get("save_image");
        if ($save_image) {
            $image_saves = $this->uploadImage($save_image, "public/images/all", "images/resize/all");
            $image_obj = ImageModel::firstOrCreate([
                "name" => $request->__get("file_name"),
                "file_name" => $image_saves["file_name"] ?? null,
                "file_path" => $image_saves["file_path"] ?? null,
                "file_url" => $image_saves["file_url"] ?? null,
                "resize_name" => $image_saves["resize_name"] ?? null,
                "resize_path" => $image_saves["resize_path"] ?? null,
                "resize_url" => $image_saves["resize_url"] ?? null
            ]);
            return redirect(route("admin_file_list"))->with("success", "created new file!");
        }
    }
}
