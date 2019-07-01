<?php

namespace App\Http;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/**
 * Class Helper
 * @package App\Http
 */
class Helper
{
    /**
     * Generate translatable url with params.
     *
     * @param string $basePath
     * @param array|string $params
     * @return string
     */
    public static function generateTranslatableUrl(string $basePath, $params): string
    {
        if (!$params) {
            return LaravelLocalization::getLocalizedURL(null, $basePath);
        }

        if (is_string($params) && !empty($_GET[$params])) {
            return LaravelLocalization::getLocalizedURL(null, "$basePath?$params={$_GET[$params]}");
        } elseif (is_array($params)) {
            $urlParams = [];
            foreach ($params as $param) {
                if ($param && !empty($_GET[$param])) {
                    $urlParams[] = "$param={$_GET['$param']}";
                }
            }
            $url = $urlParams ? $basePath . '?' . implode('&', $urlParams) : $basePath;

            return LaravelLocalization::getLocalizedURL(null, $url);
        }

        return LaravelLocalization::getLocalizedURL(null, $basePath);
    }
}
