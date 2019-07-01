<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

/**
 * Class File
 *
 * @package App\Helpers
 */
class File
{

    /**
     * Generate directory path.
     *
     * @param string|NULL $folder - prefix folder
     * @param int $depth - path depth
     * @param int $length - folder name length
     *
     * @return string
     */
    public static function generateDirPath(string $folder = null, int $depth = 2, int $length = 2): string
    {
        $path = $folder ? [$folder] : [];
        for ($i = 0; $i < $depth; $i++) {
            $path[] = str_random($length);
        }

        return implode(DIRECTORY_SEPARATOR, $path);
    }

    /**
     * Generate folder to keep temp files.
     *
     * @param string|NULL $folder
     *
     * @return string
     */
    public static function generateTempSessionPath(string $folder = null): string
    {
        $path = TEMP_STORAGE_PATH . DIRECTORY_SEPARATOR;
        if ($folder) {
            $path .= $folder . DIRECTORY_SEPARATOR;
        }
        $path .= Session::getId();

        return $path;
    }

    /**
     * Store base64 encoded image in the storage.
     *
     * @param string $base64
     * @param string $path
     * @param null $filename
     *
     * @return bool|string
     */
    public static function storeBase64Image(string $base64, string $path, $filename = null)
    {
        $ext = self::getFileExtensionFromBase64($base64);
        $filename = $path . DIRECTORY_SEPARATOR . ($filename ?: str_random()) . $ext;
        $base64 = substr($base64, strpos($base64, ",") + 1);
        $image = base64_decode($base64);

        return Storage::put($filename, $image) ? $filename : false;
    }

    /**
     * Get file size from base64 encoded file.
     *
     * @param string $base64
     *
     * @return float|int
     */
    public static function getFileSizeFromBase64(string $base64)
    {
        $koef = substr($base64, -2) == '==' ? 2 : 1;
        $size = (strlen($base64) * (3/4)) - $koef;

        return $size;
    }

    /**
     * Get file extension from base64 encoded file.
     *
     * @param string $base64
     *
     * @return string
     */
    public static function getFileExtensionFromBase64(string $base64): string
    {
        $base64 = substr($base64, strpos($base64, ",") + 1);
        $image = base64_decode($base64);
        $meta = getimagesizefromstring($image);

        return image_type_to_extension($meta[2]);
    }

    /**
     * Encode image to base64 string.
     *
     * @param string $url
     *
     * @return string
     */
    public static function encodeImageToBase64(string $url): string
    {
        $type = pathinfo($url, PATHINFO_EXTENSION);
        $data = file_get_contents($url);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        return $base64;
    }
}
