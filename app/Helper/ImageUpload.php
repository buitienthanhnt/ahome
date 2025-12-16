<?php

namespace App\Helper;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

/**
 * php artisan storage:link
 */
trait ImageUpload
{
    use Nan;

    /**
     * @param \Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]|array|null $file
     * @param string $save_folder
     * @param string $resize_path
     * @return string|bool|array{resize_name: string, resize_path: string, resize_url: string, file_path: string, file_name: string, file_url: string}
     */
    public function uploadImage($file, $save_folder = "public/images", $resize_path = null)
    {
        if ($file) {
            try {
                // $upload_file = $file->store("public/images/writer");  // lưu trực tiếp với tên file tải lên.
                $file_name = $file->getClientOriginalName();
                $file_type = $file->getClientOriginalExtension();
                $upload_name = random_int(1, 10000) . "-" . Str::random(8) . "-" . $file_name;

                $upload_file = $file->storeAs($save_folder, $upload_name); // storage/images/all/9157-FTgJTfGq-TTT-3852-1682000802-1682040277.jpg
                if (strpos($upload_file, "public") === 0) {
                    $upload_file = "storage/" . explode("/", $upload_file, 2)[1];
                }
                if ($upload_file) {
                    $file_path = asset($upload_file);
                    if ($resize_path) {
                        return array_merge(
                            [
                                'file_name' => $upload_name,
                                "file_path" => $upload_file,
                                "file_url" => $file_path
                            ],
                            // $this->resize($resize_path, $upload_file)
                            );
                    }
                    return $file_path;
                }
            } catch (\Throwable $th) {
                throw $th;
                return false;
            }
        }
        return false;
    }

    /**
     * @return null|array{resize_name: string, resize_path: string, resize_url: string}
     */
    public function resize($save_folder = null, string $file_path, $width = 400, $height = 400, $file_name = null)
    {
        if (!$save_folder) {
            $save_folder = "/storage/resize";
        } else {
            $save_folder = "storage/" . $save_folder; // /storage/images/resize/all
        }
        if (!$this->create_folder($save_folder)) {
            return;
        }

        if (!$file_name) {
            $file_name = Str::random(12) . str_replace(dirname($file_path) . "/", "", $file_path);
        }

        $real_image_path = $this->public_storage_path($file_path);

        $image =ImageManager::imagick()->read($real_image_path);
        // $save_file_name = $this->public_storage_path($save_folder) . "/" . $file_name;
        $save_file_name = $save_folder . "/" . $file_name;  // /storage/images/resize/all/lFLIAAxDjwYg9157-FTgJTfGq-TTT-3852-1682000802-1682040277.jpg

        $image->resize($width, $height)->save($save_file_name);  // lưu ý các folder phải tồn tại(nó không tạo folder tự động)
        return [
                "resize_name" => $file_name,
                "resize_path" => $save_file_name,
                "resize_url" => asset($save_file_name)
            ];
    }

    /**
     * delete file by url file path
     *
     * @param string $file_path
     * @return bool
     */
    protected function delete_file(string $file_path = "")
    {
        $a = $this->all_of_folder();
        if ($file_path) {
            if ($real_path = $this->url_to_real($file_path)) {
                $val = $this->delete_real_file($real_path);
                return $val;
            }
        }
        return false;
    }

    public function all_of_folder($dir = "storage/images")
    {
        $check_dir = File::isDirectory($dir);
        if ($check_dir) {
            $all_files = File::allFiles($dir);
            $all_file_paths = null;
            if (count($all_files)) {
                foreach ($all_files as $file) {
                    $all_file_paths[] = $this->real_to_url((string) $file); // "/var/www/html/laravel1/public/storage/images/writer/6441-rxxiRb1b-TTT-3856-1682000803-1682040279.jpg"
                }
                return $all_file_paths;
            }
        }
    }

    public function url_to_real($url_path = "")
    {
        $real_path = null;
        if ($url_path) {
            $url_path = parse_url($url_path)['path'];
            // /var/www/html/laravel1/storage/app/public/images/all/5299-B5LkucU8-TTT-3840-1682000804-1682040272.jpg
            // $root_path = $this->storage_real_path().(str_replace(asset("storage"), "", $url_path));
            $path = str_replace(parse_url(asset("storage"))['path'], "", $url_path); // storage/images/all/5299-B5LkucU8-TTT-3840-1682000804-1682040272.jpg
            if (File::exists($check_path = urldecode($this->storage_real_path().$path))) {
                $real_path = $check_path;
            }
        }
        return $real_path;
    }

    /**
     * @param string $real_path
     * @return string
     */
    public function real_to_url(string $real_path)
    {
        $url_path = null;
        if ($real_path && File::exists($real_path)) {
            if (strpos($real_path, "/") === 0) {
                $file_storage = str_replace($this->storage_real_path(), "", $real_path);
                $url_path = asset((strpos($file_storage, "/") === 0 ? "storage" : "").$file_storage);
            }else{
                $url_path = asset($real_path);
            }
        }
        return $url_path;
    }

    /**
     * @param string $path
     * @return bool
     */
    public function delete_real_file(string $path)
    {
        $check_file_path = File::exists($path);
        if ($check_file_path) {
            return File::delete($path);
        }
        return false;
    }

    /**
     * @return string
     */
    public function storage_real_path()
    {
        $storage_config =config("filesystems")["links"];
        $storage_real = key($storage_config);
        return $storage_config[$storage_real];

    }
}
