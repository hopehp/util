<?php

/**
 * This is part of Hope framework
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