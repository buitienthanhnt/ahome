<?php

namespace App\Helper;

use App\Api\BaseApi;
use App\Constant\AttributeInterface;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 *
 */
trait Nan
{
    /**
     * @return bool
     */
    public function create_folder(string $path_folder, $permission = 0777, $recursive = true)
    {
        try {
            $new_path_folder = $this->public_storage_path($path_folder);
            if (!is_dir($new_path_folder)) {
                File::makeDirectory($new_path_folder, $permission, $recursive);
            }
        } catch (\Throwable $th) {
            return false;
        }
        return true;
    }

    public function public_storage_path($path)
    {
        return public_path($path);
    }

    public static function paperCategoryTable()
    {
        return "paper_category";
    }

    public function paperTagTable()
    {
        return "paper_tag";
    }

    public function pagePriceTable()
    {
        return "price";
    }

    public function permissionRulesTable()
    {
        return "permission_rules";
    }

    public static function userPermissionTable(): string
    {
        return "admin_user_permissions";
    }

    public static function coreConfigTable(): string
    {
        return "core_config";
    }

    /**
     * string $image_path
     * @return string
     */
    function getImageUrl($image_path)
    {
        if (!$image_path){
            /**
             * get default url config.
             */
            return BaseApi::getDefaultImagePath();
        }

        $storage_path = getStoragePath();
        /**
         * support for url with this domain
         */
        if (str_starts_with($image_path, $storage_path)) {
            return url(Storage::url(str_replace($storage_path, '', $image_path)));
        }
        /**
         * support if has: /storage/ for other domain
         */
        if (strpos($image_path, '/storage/')) {
            return url(Storage::url(explode("/storage/", $image_path, 2)[1]));
        }
        /**
         * support for third party source.
         */
        if (str_starts_with($image_path, "https://") || str_starts_with($image_path, "http://")) {
            return $image_path;
        }
        /**
         * support for file path.
         */
        return url(Storage::url($image_path));
    }

    public function replaceImageUrl(string $imageUrl = ""): string
    {
        if (!$imageUrl) {
            return $this->defaultUrl();
        }
        try {
            /**
             * @var HelperFunction $helperFunction
             */
            $helperFunction = App::make(HelperFunction::class);
            // https://magento23x.jmango360.dev/pub/laravel1/
            if ($target_domain_val = $helperFunction->getConfig("target_domain")) {
                $ex_image_path = (explode('public/storage', $imageUrl));
                return $target_domain_val . 'public/storage' . $ex_image_path[1];
            }
            $domain = $helperFunction->getConfig("domain");
            $main = $helperFunction->getConfig("main");
            $ip = $helperFunction->getConfig("ip");
            if (!$domain || !$main || !$ip){return $imageUrl;}
            // support for windown platform
            return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? str_replace($domain, $ip, $imageUrl) : str_replace($domain, $ip . "/" . $main . "/public", $imageUrl);
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $imageUrl;
    }
}
