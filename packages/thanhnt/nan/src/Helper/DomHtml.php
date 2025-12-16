<?php

namespace Thanhnt\Nan\Helper;

/**
 *
 */
trait DomHtml
{

    /**
     * load html dom for read
     *
     * @param string $html_text
     */
    public function loadDom($html_text)
    {
        $doc = new \DOMDocument();
        $internalErrors = libxml_use_internal_errors(true);
        @$doc->loadHTML(mb_convert_encoding($html_text, 'HTML-ENTITIES', 'UTF-8'));
        libxml_use_internal_errors($internalErrors);
        return $doc;
    }

    /**
     * get element by element type or class
     *
     * @param [type] $doc
     * @param string $type
     * @param string $value
     * @return mixed
     */
    public function findByXpath($doc, $type = "", $value = "")
    {
        $nodes = null;
        $xpath = new \DOMXPath($doc);
        if ($type === "class") {
            $nodes = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $value ')]");
        } else {
            $nodes = $xpath->query("//$type");
        }
        return $nodes;
    }

    /**
     * @param [type] $doc 
     * @param string $id
     */
    public function geElementById($doc, $id = '')
    {
        return $doc->getElementById($id);
    }

    /**
     * get all html element conten
     *
     * @param [type] $node
     * @return string
     */
    public function getNodeHtml($node)
    {
        if (empty($node)) {
            return '';
        }
        $html = $node->ownerDocument->saveHTML($node);
        //(dantri error with image)
        // <img
        //     title="Không quân, pháo binh Nga hiệp đồng tác chiến chặn đà tiến của Ukraine - 1"
        //     src="data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20viewBox%3D'0%200%20680%20453'%3E%3Crect%20x='0'%20y='0'%20width='100%'%20height='100%'%20style='fill:rgb(241,%20245,%20249)'%20%2F%3E%3C%2Fsvg%3E"
        //     alt="Không quân, pháo binh Nga hiệp đồng tác chiến chặn đà tiến của Ukraine - 1" data-width="680"
        //     data-height="453"
        //     data-original="https://icdn.dantri.com.vn/2023/06/17/phan-cong-ukrainereuters-crop-1681224851660-1686975211997.jpeg"
        //     data-photo-id="2511139" data-track-content="" data-content-name="article-content-image"
        //     data-content-piece="article-content-image_2511139"
        //     data-content-target="/the-gioi/khong-quan-phao-binh-nga-hiep-dong-tac-chien-chan-da-tien-cua-ukraine-20230617111944211.htm"
        //     data-src="https://icdn.dantri.com.vn/thumb_w/680/2023/06/17/phan-cong-ukrainereuters-crop-1681224851660-1686975211997.jpeg"
        // >
        // dd($html);
        $html = preg_replace('/src="data:image/', 'data-old="data:image/', $html); // for dantri.com.vn
        $html = str_replace("data-src", "src", $html);                             // for dantri.com.vn
        return $html;
    }

    /**
     * get document title for all.
     *
     * @param [type] $doc
     * @return string
     */
    public function getTitle($doc)
    {
        if (count($doc->getElementsByTagName('title'))) {
            return trim($doc->getElementsByTagName('title')[0]->textContent);
        }
        return "";
    }

    /**
     * Undocumented function
     * $ss = cut_str("Mới nhất vụ tấn công 2 trụ sở xã ở Đắk Lắk: Người dân hỗ trợ công an bắt giữ", 100, "...");
     *
     * @param string $str
     * @param integer $len
     * @param string $noi
     * @return string
     */
    public function cut_str($str = "", $len = 0, $noi = "")
    {
        $result = "";
        if ($str && $len) {
            if (strlen($str) <= $len) {
                $result = $str;
            } else {
                $arrs = array_values(array_filter(explode(" ", $str)));
                $i = 0;
                while (strlen($result) + strlen($arrs[$i]) + 1 + strlen($noi) <= $len) {
                    $result .= ($i == 0 ? "" : " ") . $arrs[$i];
                    $i++;
                }
                $result .= $noi;
            }
        }
        return $result;
    }

    /**
     * @param string $file
     * đọc nội dung file.
     */
    public function read_file(string $file): string
    {
        $r_file = fopen($file, "r");
        $old_text = fread($r_file, filesize($file)); // read file
        fclose($r_file);
        return $old_text;
    }

    /**
     * $type = "w" là ghi đè nội dung file; $type = "a" là ghi nối tiếp với nội dung cũ.
     * trả về số lượng ký tự mới thêm vào.
     */
    public function write_file($file, $text_value = "", $type = "w"): int
    {
        $w_file = fopen($file, $type);
        $res = fprintf($w_file, $text_value); // tra ve so luong ky tu.
        fclose($w_file);
        return $res;
    }

    /**
     * ghi nối tiếp nội dung
     * $return_text = false: trả về số lượng ký tự mới ghi thêm. $return_text = true: trả về nội dung file mới.
     */
    public function add_text($file, $text_value, $return_text = false)
    {
        $old_text = $this->read_file($file);
        $write_length = $this->write_file($old_text . "\n" . $text_value);
        if ($return_text) {
            return $this->read_file($file);
        }
        return $write_length;
    }

    /**
     * tạo mới file bằng: "fopen" dùng đuôi: "a" hoặc "w". nếu chưa tồn tại sẽ tạo file mới, có rồi sẽ mở file đó.
     */
    function create_file($file)
    {
        $new_file = fopen($file, "a");
        fclose($new_file);
    }
}
