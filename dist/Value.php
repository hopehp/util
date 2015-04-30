<?php

namespace Hope\Util
{

    /**
     * Class Value
     *
     * @package Hope\Util
     */
    class Value
    {

        /**
         * Working value
         *
         * @var mixed|null
         */
        protected $_value;

        /**
         * @param mixed $value [optional]
         */
        public function __construct($value = null)
        {
            $this->_value = $value;
        }

        /**
         * Returns value
         *
         * @param null $default
         *
         * @return mixed
         */
        public function get($default = null)
        {
            if ($this->isEmpty() || $this->isNull()) {
                if (is_callable($default)) {
                    return call_user_func($default);
                }
                return $default;
            }
            return $this->_value;
        }

        /**
         * Checks if value is null
         *
         * @return bool
         */
        public function isNull()
        {
            return is_null($this->_value);
        }

        /**
         * Checks if value is empty
         *
         * @return bool
         */
        public function isEmpty()
        {
            return empty($this->_value);
        }

        /**
         * Checks if value is array
         *
         * @return bool
         */
        public function isArray()
        {
            return is_array($this->_value);
        }

        /**
         * Checks if value is object
         *
         * @return bool
         */
        public function isObject()
        {
            return is_object($this->_value);
        }

        /**
         * Checks if value is scalar
         *
         * @return bool
         */
        public function isScalar()
        {
            return is_scalar($this->_value);
        }

        /**
         * Checks if value is numeric
         *
         * @return bool
         */
        public function isNumber()
        {
            return is_numeric($this->_value);
        }

        /**
         * Checks if value is double number
         *
         * @return bool
         */
        public function isDouble()
        {
            return is_double($this->_value);
        }

        /**
         * Checks if value is boolean
         *
         * @return bool
         */
        public function isBoolean()
        {
            return is_bool($this->_value);
        }

        /**
         * Checks if value is callable
         *
         * @return bool
         */
        public function isCallable()
        {
            return is_callable($this->_value);
        }

        /**
         * Create new Value instance
         *
         * @param mixed $value [optional]
         *
         * @return Value
         */
        public static function make($value = null)
        {
            return new static($value);
        }

    }

}