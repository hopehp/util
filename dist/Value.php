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