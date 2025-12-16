<?php
use Illuminate\Support\Facades\Storage;

if (! function_exists('getStoragePath')) {
    /**
     * @return string
     */
    function getStoragePath() {
        return url(Storage::url('')).'/';
    }
}
if (! function_exists('fileStorageUrl')) {
    /**
     * @param string $path
     * @return string
     */
    function fileStorageUrl(string $path = '') {
        return url(Storage::url($path));
    }
}