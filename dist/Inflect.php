<?php

namespace Hope\Util
{

    /**
     * Class Inflect
     *
     * @package Hope\Util
     */
    class Inflect
    {

        /**
         * Converts string to CameCase
         *
         * @param string $string
         *
         * @return string
         */
        public static function toCamelCase($string)
        {
            return preg_replace_callback('/(^|_|\s)(.)/', function ($chunk) {
                    return strtoupper($chunk[2]);
                },
                strval($string)
            );
        }

        /**
         * Converts CamelCase string to snake_case
         *
         * @param string $string
         *
         * @return string
         */
        public static function toSnakeCase($string)
        {
            $string = preg_replace('/([a-z\d])([A-Z])/', '\1_\2', strval($string));
            $string = preg_replace('/([A-Z]+)([A-Z])/', '\1_\2', $string);

            return strtolower($string);
        }

    }

}