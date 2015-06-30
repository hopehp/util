<?php
/**
 * Hope - PHP 5.6 framework
 *
 * @author      Shvorak Alexey <dr.emerido@gmail.com>
 * @copyright   2011 Shvorak Alexey
 * @version     0.1.0
 * @package     Hope
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
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

        /**
         * Detect file content mime type
         *
         * @param File|string $file
         *
         * @return bool|string
         */
        public static function detect($file)
        {
            if ($file instanceof File) {
                $file = $file->getPath();
            }
            if (file_exists($file) && is_file($file)) {
                return mime_content_type($file);
            }

            return false;
        }

    }

}