<?php

namespace Hope\Util
{

    use Hope\Core\Error;

    /**
     * Class String
     *
     * @package Hope\Util
     */
    class String implements \ArrayAccess
    {

        /**
         * String value
         *
         * @var string
         */
        protected $_value;

        /**
         * Construct String object
         *
         * @param string $value
         *
         * @throws \Hope\Core\Error
         */
        function __construct($value)
        {
            if (false === is_string((string) $value)) {
                throw new Error(['Value must be a string, %s given', gettype($value)]);
            }
            $this->_value = (string) $value;
        }

        /**
         * Returns string value
         *
         * @return string
         */
        function __toString()
        {
            return $this->_value;
        }


        /**
         * Returns the number of string bytes
         *
         * @return int
         */
        public function bytes()
        {
            return mb_strlen($this->_value, '8bit');
        }

        /**
         * Returns string length
         *
         * @return int
         */
        public function length()
        {
            return mb_strlen($this->_value);
        }

        /**
         * Append value to string
         *
         * @param string $value
         *
         * @return \Hope\Util\String
         */
        public function append($value)
        {
            $this->_value .= $value;
            return $this;
        }

        /**
         * Prepend value to string
         *
         * @param string $value
         *
         * @return \Hope\Util\String
         */
        public function prepend($value)
        {
            $this->_value = $value . $this->_value;
            return $this;
        }

        /**
         * Checks if string contains given value
         *
         * @param string  $value
         * @param integer $offset [optional]
         *
         * @return bool
         */
        public function contains($value, $offset = null)
        {
            return $this->position($value, $offset) >= 0;
        }

        /**
         * Returns position of given value if string contains his
         *
         * @param string  $value
         * @param integer $offset [optional]
         *
         * @return int
         */
        public function position($value, $offset = null)
        {
            return mb_strpos($this->_value, $value, $offset);
        }

        /**
         * Returns all positions
         *
         * @param string  $value
         * @param integer $offset [optional]
         *
         * @return array|bool
         */
        public function positions($value, $offset = null)
        {
            $count = mb_substr_count($this->_value, $value);
            $result = [];

            while ($count--) {
                $pos = $this->position($value, $offset);
                if ($pos >= 0) {
                    $offset = $pos + 1;
                    $result[] = $pos;
                }
            }

            return $result ? : false;
        }

        /**
         * Format string
         *
         * @param array $values
         *
         * @return \Hope\Util\String
         */
        public function format(...$values)
        {
            if (is_array($values)) {
                $this->_value = vsprintf($this->_value, $values);
            } else {
                $this->_value = sprintf($this->_value, ...$values);
            }
            return $this;
        }

        /**
         * Repeat string given times
         *
         * @param integer $times
         *
         * @return \Hope\Util\String
         */
        public function repeat($times)
        {
            return $this->setValue(str_repeat($this->_value, (int) $times));
        }

        /**
         * @param $pattern
         * @param $replace
         *
         * @return \Hope\Util\String
         */
        public function replace($pattern, $replace)
        {
            // TODO : Write code
            return $this;
        }

        /**
         * Shuffle string value
         *
         * @return \Hope\Util\String
         */
        public function shuffle()
        {
            return $this->setValue(str_shuffle($this->_value));
        }


        /**
         * Reverse string
         *
         * @return \Hope\Util\String
         */
        public function reverse()
        {
            return $this->setValue(strrev($this->_value));
        }

        /**
         * Convert string to upper case
         *
         * @return \Hope\Util\String
         */
        public function toUpper()
        {
            return $this->setValue(mb_strtoupper($this->_value));
        }

        /**
         * Convert string to lower case
         *
         * @return \Hope\Util\String
         */
        public function toLower()
        {
            return $this->setValue(mb_strtolower($this->_value));
        }

        /**
         * Convert string to canonize case
         *
         * Example: this is a great thing -> This is a great thing
         *
         * @return \Hope\Util\String
         */
        public function toCanonize()
        {
            $this->toLower();
            $this->_value[0] = mb_strtoupper($this->_value[0]);

            return $this;
        }

        /**
         * Split string to chunks by given size
         *
         * @param integer $size
         *
         * @return array
         */
        public function chunk($size = 1)
        {
            return str_split($this->_value, $size);
        }

        /**
         * Split string and return chunks
         *
         * @param string  $pattern
         * @param integer $limit [optional]
         *
         * @return array
         */
        public function split($pattern, $limit = null)
        {
            return mb_split($pattern, $this->_value, $limit);
        }

        /**
         * Trim right
         *
         * @param string $chars [optional]
         *
         * @return \Hope\Util\String
         */
        public function trimRight($chars = null)
        {
            if (false === is_null($chars)) {
                return $this->setValue(rtrim($this->_value, $chars));
            }
            return $this->setValue(rtrim($this->_value));
        }

        /**
         * Trim left
         *
         * @param string $chars [optional]
         *
         * @return \Hope\Util\String
         */
        public function trimLeft($chars = null)
        {
            if (false === is_null($chars)) {
                return $this->setValue(ltrim($this->_value, $chars));
            }
            return $this->setValue(ltrim($this->_value));
        }

        /**
         * Trim string
         *
         * @param string $chars [optional]
         *
         * @return \Hope\Util\String
         */
        public function trim($chars = null)
        {
            if (false === is_null($chars)) {
                return $this->setValue(trim($this->_value, $chars));
            }
            return $this->setValue(trim($this->_value));
        }

        /**
         * Create string hash
         *
         * @param string $algo Algorithm name
         *
         * @throws \Hope\Core\Error
         *
         * @return \Hope\Util\String
         */
        public function hash($algo)
        {
            if (false === in_array($algo, hash_algos())) {
                throw new Error(['Hash algorithm %s not available', $algo]);
            }
            return $this->setValue(hash($algo, $this->_value));
        }

        /**
         * Create hash from string using sha1 algorithm
         *
         * @throws \Hope\Core\Error
         *
         * @return \Hope\Util\String
         */
        public function sha1()
        {
            return $this->hash('sha1');
        }

        /**
         * Create hash from string using md5 algorithm
         *
         * @throws \Hope\Core\Error
         *
         * @return \Hope\Util\String
         */
        public function md5()
        {
            return $this->hash('md5');
        }

        /**
         * Set value
         *
         * @param string $value
         *
         * @return \Hope\Util\String
         */
        public function setValue($value)
        {
            $this->_value = $value;
            return $this;
        }

        /**
         * Returns string
         *
         * @return string
         */
        public function getValue()
        {
            return $this->_value;
        }

        /**
         * @inheritdoc
         */
        public function offsetExists($offset)
        {
            return isset($this->_value[$offset]);
        }

        /**
         * @inheritdoc
         */
        public function offsetGet($offset)
        {
            return $this->_value[$offset];
        }

        /**
         * @inheritdoc
         */
        public function offsetSet($offset, $value)
        {
            $this->_value[$offset] = $value;
        }

        /**
         * @inheritdoc
         */
        public function offsetUnset($offset)
        {
            // TODO: Implement offsetUnset() method.
        }

    }

}