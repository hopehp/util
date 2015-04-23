<?php

namespace Hope\Util
{

    /**
     * Class Mime
     *
     * @package Hope\Util
     */
    class Mime
    {

        protected static $mimes = [
            // Text
            'text/html' => [
                'shtml',
                'html',
                'htm',
            ],
            'text/css' => 'css',
            'text/xml' => 'xml',
            'text/mathml' => 'mml',
            'text/plain' => 'txt',

            // Images
            'image/bmp' => 'bmp',
            'image/gif' => 'gif',
            'image/png' => 'png',
            'image/jpeg' => [
                'jpeg',
                'jpg',
                'jpe',
            ],
            'image/tiff' => [
                'tiff',
                'tif'
            ],
            'image/svg+xml' => [
                'svg',
                'svgz'
            ],
            'image/vnd.microsoft.icon' => 'ico',

            // Applications
            'application/javascript' => 'js',
            'application/font-woff' => 'woff',
            'application/atom+xml' => 'atom',
            'application/rss+xml' => 'rss',
            'application/json' => 'json',
        ];

        /**
         * Returns extension by mime type
         *
         * @param string $mime
         *
         * @return string|null
         */
        public static function getExtension($mime)
        {
            if (isset(static::$mimes[$mime])) {
                return is_array(static::$mimes[$mime])
                    ? reset(static::$mimes[$mime])
                    : static::$mimes[$mime];
            }
            return null;
        }

        /**
         * Returns extensions for mime type
         *
         * @param string $mime
         *
         * @return array|null
         */
        public static function getExtensions($mime)
        {
            if (isset(static::$mimes[$mime])) {
                return (array) static::$mimes[$mime];
            }
            return null;
        }

        /**
         * Returns mime type for extension
         *
         * @param string $extension
         *
         * @return string|null
         */
        public static function getMime($extension)
        {
            foreach (static::$mimes as $mime => $ext) {
                if (is_string($ext) && $ext === $extension) {
                    return $mime;
                }
                if (is_array($ext) && in_array($extension, $ext)) {
                    return $mime;
                }
            }
            return null;
        }

    }

}