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
     * Class System
     *
     * @package Hope\Util
     *
     * @author Shvorak Alexey <dr.emerido@gmail.com>
     * @since 0.0.1
     */
    class System
    {

        /**
         * Returns `true` if OS is x64
         *
         * @return bool
         */
        public static function is64bit()
        {
            return 9223372036854775807 == intval('9223372036854775807');
        }

        /**
         * Returns `true` if OS is x32
         *
         * @return bool
         */
        public static function is32bit()
        {
            return 2147483647 == intval('9223372036854775807');
        }

        /**
         * Returns `true` if OS is `Windows`
         *
         * @return bool
         */
        public static function isWindows()
        {
            return 'Windows' == static::getName();
        }

        /**
         * Returns `true` if OS is `Darwin`
         *
         * @return bool
         */
        public static function isDarwin()
        {
            return 'Darwin' == static::getName();
        }

        /**
         * Returns `true` if OS is `FreeBSD`
         *
         * @return bool
         */
        public static function isFreeBSD()
        {
            return 'FreeBSD' == static::getName();
        }

        /**
         * Returns `true` if OS is `Linux`
         *
         * @return bool
         */
        public static function isLinux()
        {
            return 'Linux' == static::getName();
        }

        /**
         * Returns OS name
         *
         * @return string
         */
        public static function getName()
        {
            $name = PHP_OS;
            if (strtoupper(substr($name, 0, 3)) === 'WIN') {
                $name = 'Windows';
            }
            return $name;
        }

    }

}