<?php

namespace App\ViewBlock;

use App\Models\Category;
use App\Models\Writer;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\Request;
use Thanhnt\Nan\Helper\RemoteSourceManager;

class PaperCreateForm implements Htmlable
{
    protected $request;
    protected $category;
    protected $remoteSourceManager;

    function __construct(
        Request $request,
        Category $category,
        RemoteSourceManager $remoteSourceManager
    ) {
        $this->request = $request;
        $this->category = $category;
        $this->remoteSourceManager = $remoteSourceManager;
    }

    function toHtml(): string
    {
        $contenType = $this->request->get('type', "content");
        $data = null;
        switch ($contenType) {
            case 'carousel':
                $data = $this->carouselForm();
                break;
            case 'product':
                $data = $this->productForm();
                break;
            default:
                $data = $this->contenForm();
        }
        return view($data['template'], $data['params'])->render();
    }

    function contenForm()
    {
        if ($source_request = $this->request->get("source_request")) {
            $remoteData = $this->remoteSourceManager->source($source_request);
        }
        return [
            'template' => "adminhtml.templates.papers.forms.contenForm",
            "params" => array_merge($remoteData ?? [], [
                "category_option" => $this->category->category_tree_option(),
                "time_line_option" => $this->category->time_line_option(),
                "writers" => Writer::all()
            ])
        ];
    }

    function carouselForm()
    {
        return [
            'template' => "adminhtml.templates.papers.forms.carouselForm",
            "params" => [
                "category_option" => $this->category->category_tree_option(),
                "time_line_option" => $this->category->time_line_option(),
                "writers" => Writer::all()
            ]
        ];
    }

    function productForm(): array
    {
        if ($source_request = $this->request->get("source_request")) {
            $remoteData = $this->remoteSourceManager->source($source_request);
        }
        return [
            'template' => "adminhtml.templates.papers.forms.productForm",
            "params" => array_merge($remoteData ?? [], [
                "category_option" => $this->category->category_tree_option(),
                "time_line_option" => $this->category->time_line_option(),
                "filemanager_url_mce" => url("adminhtml/file/manager") . "?editor=tinymce5",
                "filemanager_url_base" => url("adminhtml/file/manager"),
                "writers" => Writer::all()
            ])
        ];
    }
}
