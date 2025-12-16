<?php

namespace Thanhnt\Nan\Helper;

use Illuminate\Http\Request;
use Thanhnt\Nan\Helper\DomHtml;
use App\Models\RemoteSourceHistory;
use Exception;
use Illuminate\Support\Facades\Response;

class RemoteSourceManager
{
    use DomHtml;
    use StringHelper;

    const SOURCE = [
        "soha.vn"           => "get_soha_value",
        "vietnamnet.vn"     => "get_vietnamnet_value",
        "github.com"        => "get_gitgub_value",
        "tienphong.vn"      => "tienphong_vn",
        "tuoitre.vn"        => "get_tuoitre_vn",
        "giaoducthoidai.vn" => "get_giaoducthoidai_vn",
        "viblo.asia"        => "get_viblo_asia",
        "dinhnt.com"        => "get_dinhnt_com",
        "xuanthulab.net"    => "get_xuanthulab_net",
        "hoclaptrinh.vn"    => "get_hoclaptrinh_vn",
        "sql.js.org"        => "get_sql_js_org",
        "vtc.vn"            => "get_vtc_vn",
        "www.qdnd.vn"       => "get_www_qdnd_vn",
        "www.delftstack.com" => "get_www_delftstack_com",
        "niithanoi.edu.vn"  => "get_niithanoi_edu_vn",
        "techmaster.vn"     => "get_techmaster_vn",
        "kienthuc.net.vn"   => "get_kienthuc_net_vn",
        "www.thivien.net"   => "get_www_thivien_net",
        "dantri.com.vn"     => "get_dantri_value", // host => function
        "topdev.vn"         => "get_topdev_vn",    // host => function
        "toidicode.com"     => "get_toidicode_com",
        "freetuts.net"      => "get_freetuts_net",
        "thanhnien.vn"      => "get_thanhnien_vn",
        "laodong.vn"        => "get_laodong_vn",
        "vnexpress.net"     => "get_vnexpress_net",
        "www.w3schools.com" => "get_www_w3schools_com",
        "laracoding.com"    => "get_laracoding_com",
        "vtcnews.vn"        => "get_vtcnews_vn",
        "doanhnghiepvn.vn"  => "get_doanhnghiepvn_vn",
        "codestus.com"      => "get_codestus_com",
        "quantrimang.com"   => "get_quantrimang_com",
        "jaredchu.com"      => "get_jaredchu_com",
        "danviet.vn"        => "get_danviet_vn",
        "seongon.com"       => "get_seongon_com",
        "www.24h.com.vn"    => "get_www_24h_com_vn",
        "s.cafef.vn"        => "get_s_cafef_vn",
        "nld.com.vn"        => "get_nld_com_vn",
        "vanvn.vn"          => "get_vanvn_vn",
        "thuvienphapluat.vn" => "get_thuvienphapluat_vn",
        "plo.vn" => "get_plo_vn",
        "anonystick.com"    => "get_anonystick_com",
        "baoquocte.vn"      => "get_baoquocte_vn",
        "readerstacks.com"  => "get_readerstacks_com",
        "cafef.vn" => "get_cafef_vn"
    ];

    protected $request;
    protected $remoteSourceHistory;
    protected $logTha;

    public function __construct(
        Request $request,
        RemoteSourceHistory $remoteSourceHistory,
        LogTha $logTha
    ) {
        $this->request = $request;
        $this->remoteSourceHistory = $remoteSourceHistory;
        $this->logTha = $logTha;
    }

    /**
     * Undocumented function
     *
     * @param Request|string $request
     * @return array
     */
    public function source($request)
    {
        $request_url = is_string($request) ? $request : $this->request->get("source_request");
        if (!strlen(str_replace(" ", "", $request_url))) {
            return [];
        }
        $value = [];
        try {
            /**
             * check type of source request for get conten value.
             */
            $type = $this->check_type($request_url);
            if ($type) {
                $html = $this->file_get_contents_source($request_url);
                if (!$html) {
                    return [];
                }
                $doc = $this->loadDom($html);  // for load html text to dom
                if (method_exists($this, str_replace(".", "_", $type))) { // kiểm tra xem class có tồn tại function có tên như biến $type không.
                    $value = $this->{$type}($doc);                       // gọi vào hàm có trong class thông qua tên là 1 biến số.
                } else {
                    $value = call_user_func_array([$this, $type], [$doc]); // gọi bằng call_user_func_array() với class, method, param truyền vào.
                }
            } else {
                return [];
                return redirect()->back()->with("error", "input url not found");
            }
        } catch (\Throwable $th) {
            $this->logTha->logError('error', $th->getMessage());
            throw new \Exception($th->getMessage(), 1);
        }
        return $value;
    }

    function file_get_contents_source($url)
    {
        $html = "";
        try {
            fopen("cookies.txt", "w");
            $parts = parse_url($url);
            $host = $parts['host'];
            $ch = curl_init();
            // https://stackoverflow.com/questions/17363545/file-get-contents-is-not-working-for-some-url
            $header = array(
                'GET /1575051 HTTP/1.1',
                "Host: {$host}",
                'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language:en-US,en;q=0.8',
                'Cache-Control:max-age=0',
                'Connection:keep-alive',
                'Host:adfoc.us',
                'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36',
            );

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_ENCODING, "gzip"); // https://stackoverflow.com/questions/18398510/file-get-contents-the-contents-are-encrypted
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_COOKIESESSION, true);
            curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
            curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $html = curl_exec($ch);
            curl_close($ch);
        } catch (\Throwable $th) {
            $arrContextOptions = array( // https://www.php.net/manual/en/context.http.php
                "ssl" => array(
                    // skip error "Failed to enable crypto" + "SSL operation failed with code 1."
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
                // skyp error "failed to open stream: operation failed" + "Redirection limit reached"
                'http' => array(
                    'max_redirects' => 101,
                    'ignore_errors' => '1'
                ),
            );
            $html = file_get_contents($url, false, stream_context_create($arrContextOptions));
            if (!$html) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
                curl_setopt($ch, CURLOPT_URL, $url);
                $html = curl_exec($ch);
                curl_close($ch);
            }
        }
        return $html;
    }

    public function get_soha_value($doc)
    {
        // for: soha.vn
        return $this->getValueByClassName($doc, "detail-content", "news-sapo");
    }

    function get_vietnamnet_value($doc)
    {
        // for: vietnamnet.vn
        return $this->getValueByClassName($doc, "maincontent main-content", "content-detail-sapo");
    }

    function get_dantri_value($doc): array
    {
        // for: dantri.com.vn
        // return $this->getValueByClassName($doc, "e-magazine__body", "e-magazine__sapo");
        return $this->getValueByClassName($doc, "singular-content", "e-magazine__sapo");
    }

    public function get_gitgub_value($doc)
    {
        // for: github.com
        return $this->getValueByClassName($doc, "js-quote-selection-container", "js-issue-title markdown-title");
    }

    public function tienphong_vn($doc)
    {
        // for: tienphong.vn
        return $this->getValueByClassName($doc, "article__body cms-body", "article__sapo cms-desc");
    }

    public function get_giaoducthoidai_vn($doc)
    {
        return $this->getValueByClassName($doc, "article__body cms-body", "article__sapo cms-desc");
    }
    public function get_tuoitre_vn($doc)
    {
        return $this->getValueByClassName($doc, "detail-cmain", "detail-sapo");
    }

    function get_dinhnt_com($doc)
    {
        return $this->getValueByClassName($doc, "blog-single-1x", "overlay-text text-left");
    }

    function get_xuanthulab_net($doc)
    {
        //
        return $this->getValueByClassName($doc, "main-post", "overlay-text text-left");
    }

    function get_hoclaptrinh_vn($doc)
    {
        // https://hoclaptrinh.vn/tutorial/hoc-sqlite-co-ban-va-nang-cao/su-dung-sqlite-voi-php
        return $this->getValueByClassName($doc, "question-detail-wrapper", "");
    }

    function get_sql_js_org($doc)
    { // https://sql.js.org/#/ not work.
        return $this->getValueByClassName($doc, "content", "");
    }

    function get_viblo_asia($doc)
    {
        $conten = $this->getValueByClassName($doc, "md-contents", "");
        /**
         * format image for display in the view viblo_asia
         */
        $conten['conten'] = preg_replace('/(<canvas).*<\/canvas>/', '', $conten['conten']);
        return $conten;
    }

    function get_www_delftstack_com($doc)
    {
        return $this->getValueByClassName($doc, "col-md-9 col-sm-9 main-content", "");
    }

    function get_vtc_vn($doc)
    {
        return $this->getValueByClassName($doc, "edittor-content box-cont mt15 clearfix", "font18 bold inline-nb");
    }

    function get_www_qdnd_vn($doc)
    {
        return $this->getValueByClassName($doc, "post-content", "");
    }

    function get_quantrimang_com($doc)
    {
        return $this->getValueByClassName($doc, "content-detail", "");
    }

    function get_niithanoi_edu_vn($doc)
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "noidung TextSize noidungList", ""));
    }

    function get_techmaster_vn($doc)
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "techmaster-font-open-sans post-content", ""));
    }

    function get_kienthuc_net_vn($doc)
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "cms-body", "sapo cms-desc"));
    }

    // get_www_thivien_net
    function get_www_thivien_net($doc)
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "poem-content", "page-header"));
    }

    public function get_topdev_vn($doc)
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "td-post-content", "entry-title"));
    }

    public function get_toidicode_com($doc)
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "entry-main-content", "entry-title"));
    }

    function get_freetuts_net($doc): array
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "article", ""));
    }

    function get_thanhnien_vn($doc): array
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "detail-cmain", "detail-title"));
    }

    function get_laodong_vn($doc): array
    {
        return call_user_func(fn() => $this->getValueById($doc, "gallery-ctt", "chappeau"));
    }

    // get_vnexpress_net
    function get_vnexpress_net($doc): array
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "fck_detail", "title-detail"));
    }

    function get_www_w3schools_com($doc): array
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "l10", ""));
    }

    function get_laracoding_com($doc): array
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "entry-content"));
    }

    // get_vtcnews_vn
    function get_vtcnews_vn($doc): array
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "content-wrapper"));
    }

    // get_doanhnghiepvn_vn
    function get_doanhnghiepvn_vn($doc): array
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "single-entry-summary"));
    }

    // get_codestus_com
    function get_codestus_com($doc): array
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "prose"));
    }

    // get_jaredchu_com
    function get_jaredchu_com($doc): array
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "post-content"));
    }

    // get_danviet_vn
    function get_danviet_vn($doc): array
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "dt-content"));
    }

    function get_seongon_com($doc): array
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "elementor-element"));
    }

    // get_www_24h_com_vn
    function get_www_24h_com_vn($doc): array
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "cate-24h-foot-arti-deta-info", "cate-24h-foot-arti-deta-title"));
    }

    // get_s_cafef_vn
    function get_s_cafef_vn($doc): array
    {
        return call_user_func(fn() => $this->getValueById($doc, "newscontent", "intro"));
    }

    // get_nld_com_vn
    function get_nld_com_vn($doc): array
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "detail-cmain", "detail-sapo"));
    }

    // get_vanvn_vn
    function get_vanvn_vn($doc): array
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "entry-content"));
    }

    // get_thuvienphapluat_vn
    function get_thuvienphapluat_vn($doc): array
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, ["newcontent", "news-content"]));
    }

    // get_plo_vn
    function get_plo_vn($doc): array
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "cms-body", "cms-desc"));
    }

    // get_anonystick_com
    function get_anonystick_com($doc): array
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "post__card", "post__title"));
    }

    function get_baoquocte_vn($doc): array
    {
        return call_user_func(fn() => $this->getValueById($doc, "articleBody"));
        return array();
    }

    // get_readerstacks_com
    function get_readerstacks_com($doc): array
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "entry-content", 'entry-title'));
        return array();
    }

    // get_cafef_vn
    function get_cafef_vn($doc): array
    {
        return call_user_func(fn() => $this->getValueByClassName($doc, "contentdetail", 'sapo'));
        return array();
    }


    // ===================================================================//

    /**
     * Undocumented function
     *
     * @param string $request
     * @return void|boolean|string
     */
    protected function check_type($request)
    {
        if ($request) {
            try {
                $url_values = parse_url($request);
                if (in_array($url_values["host"], array_keys(self::SOURCE))) {
                    return method_exists($this, str_replace(".", "", $url_values["host"])) ? $url_values["host"] : self::SOURCE[$url_values["host"]];
                }
            } catch (\Throwable $th) {
            }
        }
        return false;
    }

    /**
     * @param $doc                       // source for search
     * @param string|array $class_conten       // for main of conten
     * @param string $class_short_conten // for short conten
     * @return array
     */
    protected function getValueByClassName($doc, $class_conten, $class_short_conten = '')
    {
        $request = $this->request;
        $title = $this->getTitle($doc);
        $url_alias = str_replace([":", "'", '"', "“", "”", ",", ".", "·", " ", "|", "/", "\\"], "", $this->vn_to_str($title, 1));
        $short_conten_value = "";
        $conten = "";
        try {
            $short_conten = $this->findByXpath($doc, "class", $class_short_conten);
            if (count($short_conten)) {
                $short_conten_value = trim($short_conten[0]->textContent);
            }
        } catch (\Exception $e) {
        }

        try {
            if (is_string($class_conten)) {
                $nodes = $this->findByXpath($doc, "class", $class_conten); // load content: (image error)
                $conten = $this->getNodeHtml($nodes[0]);
            } elseif (is_array($class_conten)) {
                for ($i = 0; $i < count($class_conten); $i++) {
                    $nodes = $this->findByXpath($doc, "class", $class_conten[$i]); // load content: (image error)
                    if (isset($nodes[0])) {
                        $conten = $this->getNodeHtml($nodes[0]);
                        if ($conten) {
                            break;
                        }
                    } else {
                        continue;
                    }
                }
            }
        } catch (\Exception $e) {
            dd($e);
        }

        return [
            "title" => $title,
            "url_alias" => $url_alias,
            "short_conten" => $this->cut_str(trim($short_conten_value), 250, "..."),
            "conten" => $conten,
            "active" => $request->__get("active", true),
            "show" => $request->get("show", true),
            "auto_hide" => $request->__get("auto_hide", true),
            "show_writer" => $request->__get("show_writer", true),
            "show_time" => $request->__get("show_time", true),
            "image_path" => $request->__get("image_path", ""),
            "writer" => $request->get("writer", null)
        ];
    }

    function getValueById($doc, $id_content, $class_short_content = ''): array
    {
        $request = $this->request;
        $title = $this->getTitle($doc);
        $url_alias = str_replace([":", "'", '"', "“", "”", ",", ".", "·", " ", "|", "/", "\\"], "", $this->vn_to_str($title, 1));
        $short_conten_value = "";
        try {
            $short_conten = $this->findByXpath($doc, "class", $class_short_content);
            if (count($short_conten)) {
                $short_conten_value = trim($short_conten[0]->textContent);
            }
        } catch (\Exception $e) {
        }

        return [
            "title" => $title,
            "url_alias" => $url_alias,
            "short_conten" => $this->cut_str(trim($short_conten_value), 250, "..."),
            "conten" => $this->getNodeHtml($this->geElementById($doc, $id_content)),
            "active" => $request->__get("active", true),
            "show" => $request->get("show", true),
            "auto_hide" => $request->__get("auto_hide", true),
            "show_writer" => $request->__get("show_writer", true),
            "show_time" => $request->__get("show_time", true),
            "image_path" => $request->__get("image_path", ""),
            "writer" => $request->get("writer", null)
        ];
        return [];
    }

    /**
     * check if source_uri exist
     *
     * @param string $sourceUri
     * @return bool
     */
    public function checkSourceExit(string $sourceUri)
    {
        $value = RemoteSourceHistory::firstWhere('url_value', $sourceUri);
        return (bool) $value;
    }

    public function save_remote_source_history($request_url = "", $type = null, $paper_id = null, $active = true)
    {
        // save for history
        if (!$request_url) {
            return false;
        }
        $history = new RemoteSourceHistory(["url_value" => $request_url, "type" => $type, "paper_id" => $paper_id, "active" => $active]);
        return $history->save();
    }

    function download()
    {
        $file = public_path() . "/vendor/app-release.apk";

        // $headers = array(
        //       'Content-Type: application/pdf',
        //     );

        return Response::download($file, 'app-release.apk');
    }
}
