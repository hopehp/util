<?php

namespace Hope\Util\Mixin
{

    /**
     * Class Singleton
     *
     * @package Hope\Util\Mixin
     */
    trait Singleton
    {

        /**
         * Class instance
         *
         * @var static
         */
        private static $__instance;

        /**
         * Returns class instance
         *
         * @param mixed $args
         *
         * @return static
         */
        public static function instance(...$args)
        {
            if (static::$__instance === null) {
                static::$__instance = new static(...$args);
            }

            return static::$__instance;
        }

        /**
         * Save class instance
         *
         * Call this method in constructor
         *
         * @param object $instance
         */
        protected function saveInstance($instance)
        {
            static::$__instance = $instance;
        }

    }

}