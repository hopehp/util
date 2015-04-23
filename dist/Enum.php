<?php

namespace Hope\Util
{

    use Hope\Core\Error;

    /**
     * Class Enum
     *
     * @package Hope\Util
     */
    class Enum
    {

        /**
         * Enum instance value
         *
         * @var mixed
         */
        protected $_value;

        /**
         * Constants list cache
         *
         * @var array
         */
        protected static $_cache = [];


        final public static function get($value)
        {
            return new static($value);
        }

        /**
         * @param $name
         *
         * @throws \Hope\Core\Error
         *
         * @return \Hope\Util\Enum
         */
        final public static function getByName($name)
        {
            $constants = static::getConstants();

            if (array_key_exists($name, $constants)) {
                return static::get($constants[$name]);
            }

            throw new Error(['No constant or method %s in class %s', $name, get_called_class()]);
        }

        /**
         * Check if enum contains value
         *
         * @param null|int|bool|float|string $value
         *
         * @return bool
         */
        final public static function isValid($value)
        {
            return in_array($value, static::getConstants());
        }

        /**
         * Check if enum contains value by key
         *
         * @param string $name
         *
         * @return bool
         */
        final public static function isValidKey($name)
        {
            return array_key_exists($name, static::getConstants());
        }

        /**
         * Returns enum constants
         *
         * @return array
         */
        final public static function getConstants()
        {
            return static::fetchConstants(get_called_class());
        }

        /**
         * Returns class constants
         * Fetch constants and cache
         *
         * @param string $class Class name
         *
         * @return array
         */
        final private static function fetchConstants($class)
        {
            if (false === isset(static::$_cache[$class])) {
                // Get class reflection
                $reflect = new \ReflectionClass($class);

                $constants = $reflect->getConstants();

                while (($reflect = $reflect->getParentClass()) && $reflect->name !== __CLASS__) {
                    $constants = $reflect->getConstants() + $constants;
                }

                static::$_cache[$class] = $constants;
            }

            return static::$_cache[$class];
        }

        /**
         * Compare enumerators
         *
         * @param \Hope\Util\Enum $enum
         *
         * @return bool
         */
        final public function is(Enum $enum)
        {
            return $this === $enum || $this->_value === $enum->getValue();
        }

        /**
         * Returns enumerator name
         *
         * @return mixed
         */
        final public function getName()
        {
            return array_search($this->_value, static::getConstants(), true);
        }

        /**
         * Returns value
         *
         * @return mixed
         */
        final public function getValue()
        {
            return $this->_value;
        }

        /**
         * Create enum instance
         *
         * @param mixed $value
         *
         * @throws \Exception
         */
        public function __construct($value)
        {
            if (false === static::isValid($value)) {
                throw new \Exception('Invalid value for enum');
            }
            $this->_value = $value;
        }

        /**
         * Returns value as string
         *
         * @return string
         */
        final public function __toString()
        {
            return $this->getName();
        }

        /**
         * @inheritdoc
         */
        final public function __clone()
        {
            throw new \LogicException('Can\'t clone enum');
        }

        /**
         * @inheritdoc
         */
        final public function __sleep()
        {
            throw new \LogicException('Can\'t serialize enum');
        }

        /**
         * @inheritdoc
         */
        final public function __wakeup()
        {
            throw new \LogicException('Can\'t serialize enum');
        }

        /**
         * Return enum instance if is valid
         *
         * @param string $name
         * @param array  $arguments
         *
         * @throws \Hope\Core\Error
         *
         * @return \Hope\Util\Enum
         */
        final public static function __callStatic($name, $arguments)
        {
            return static::getByName($name);
        }

    }

}