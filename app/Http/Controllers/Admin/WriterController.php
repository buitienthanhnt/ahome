<?php

namespace App\Http\Controllers\Admin;

use App\Api\PaperFirebaseApi;
use App\Helper\ImageUpload;
use App\Http\Controllers\Controller;
use App\Models\Writer;
use App\Models\WriterInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WriterController extends Controller implements WriterControllerInterface
{
    use ImageUpload;

    protected $request;
    protected $writer;

    protected $paperFirebaseApi;

    public function __construct(
        Request $request,
        Writer $writer,
        PaperFirebaseApi $paperFirebaseApi
    )
    {
        $this->request = $request;
        $this->writer = $writer;
        $this->paperFirebaseApi = $paperFirebaseApi;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function listWriter()
    {
        if (view()->exists('adminhtml.templates.writer.list')) {
            $all_writer = Writer::paginate(8);
            return view("adminhtml.templates.writer.list", compact("all_writer"));
        } else {
            return redirect("adminhtml")->with("not_page", 1);
        }
    }

    public function createWriter()
    {
        if (view()->exists('adminhtml.templates.writer.create')) {
            return view("adminhtml.templates.writer.create");
        } else {
            redirect("admin")->with("no_router", 1);
        }
    }

    public function insertWriter()
    {
        $request = $this->request;
        if ($file = $request->file("image_post")) {
            $image_upload_path = $this->uploadImage($file, "public/images/writer", "images/resize/writer");
        }
        $image_path = null;
        if (isset($image_upload_path) && $image_upload_path["file_path"]){
            $image_path = url($image_upload_path["file_path"]);
            if (false){
                $image_path = $this->paperFirebaseApi->upLoadImageFirebase(url($image_upload_path["file_path"]));
            }
        }

        $writer = $this->writer;
        $writer->fill([
            "name" => $request->get("name"),
            "email" => $request->get("email"),
            "phone" => $request->get("phone"),
            "address" => $request->get("address"),
            "image_path" => $image_path,
            "name_alias" => $request->get("alias"),
            "active" => $request->get("active") === 'on' ? true : false,
            "date_of_birth" => Carbon::createFromFormat('Y-m-d', $request->get("date_of_birth")), // date('Y-m-d H:i:s', strtotime($request->__get("date_of_birth")))
            "rating" => $request->get("good") ?: null
        ]);

        $result = $writer->save();
        if ($result) {
            return redirect()->back()->with("success", "created new writer");
        } else {
            return redirect()->back()->with("error", "create fail!!, please try again.");
        }
    }

    public function deleteWriter()
    {
        try {
            if ($writer_id = $this->request->get("writer_id")) {
                $writer = $this->writer->find($writer_id);
                if ($writer && $writer->id) {
                    // delete resize file of writer
                    $this->delete_file($writer->image_path);
                    // $writer->delete // xoa mem
                    $writer->forceDelete(); // xoa han khoi ban ghi
                    return response(json_encode([
                        "code" => "200",
                        "value" => "deleted: $writer->name success"
                    ]), 200);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return response(json_encode([
            "code" => "401",
            "value" => "can not delete, please try again."
        ]), 401);
    }

    /**
     * @param int $writer_id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse
     */
    public function editWriter($writer_id)
    {
        if ($writer_id) {
            $writer = $this->writer->find($writer_id);
            if ($writer) {
                return view("adminhtml.templates.writer.edit", compact("writer"));
            }
        }
        return redirect()->back()->with("error", "can not update data!");
    }

    public function updateWriter($writer_id = null)
    {
        if ($writer_id) {
            $request = $this->request;
            $writer = $this->writer->find($writer_id);
            if ($writer) {
                if ($file = $request->__get("image_post")) {
                    $image_upload_path = $this->uploadImage($file, "public/images/writer", "images/resize/writer");
                    if ($image_upload_path && $writer->image_path) {
                        $this->delete_file($writer->image_path); // xoa file cu de thay bang file moi.
                    }
                }
                try {
                    $writer->fill([
                        WriterInterface::ATTR_NAME => $request->get("name"),
                        WriterInterface::ATTR_EMAIL => $request->get("email"),
                        WriterInterface::ATTR_PHONE => $request->get("phone"),
                        WriterInterface::ATTR_ADDRESS => $request->get("address"),
                        WriterInterface::ATTR_IMAGE_PATH => $image_upload_path["file_path"] ? url($image_upload_path["file_path"]) : null,
                        WriterInterface::ATTR_NAME_ALIAS => $request->get("alias"),
                        WriterInterface::ATTR_ACTIVE => $request->get("active") ?? true,
                        WriterInterface::ATTR_DATE_OF_BIRTH => Carbon::createFromFormat('Y-m-d', $request->get("date_of_birth")), // date('Y-m-d H:i:s', strtotime($request->__get("date_of_birth")))
                    ]);
                    $result = $writer->save();
                    if ($result) {
                        return redirect()->back()->with("success", "updated writer success!");
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                }
                return redirect()->back()->with("error", "can not update!");
            }
        } else {
            return redirect()->back()->with("error", "can not update!");
        }
    }

    public function deleteFile()
    {
        $path = $this->request->__get("path");
        if ($path) {
            $url = app('url');
            $this->delete_file($path);
        }
    }
}
