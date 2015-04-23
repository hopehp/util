<?php

namespace Hope\Util
{

    use ArrayAccess;
    use ArrayIterator;
    use IteratorAggregate;

    use Hope\Core\Error;

    /**
     * Class Set
     *
     * @package Hope\Util
     */
    class Set implements ArrayAccess, IteratorAggregate
    {

        /**
         * @var array
         */
        protected $values;

        protected $delimiter;

        /**
         * Set value(s) to set
         *
         * @param string $key
         * @param mixed  $value [optional]
         *
         * @throws \Hope\Core\Error
         *
         * @return \Hope\Util\Set
         */
        public function set($key, $value = null)
        {
            if (is_array($key)) {
                foreach ($key as $k => $v) {
                    $this->set($k, $v);
                }
            } else {
                if ($this->delimiter && false !== $pos = strpos($key, $this->delimiter)) {
                    $set = $this->get(substr($key, 0, $pos));
                    if ($set instanceof Set) {
                        $set->set(substr($key, $pos + 1), $value);
                    }
                } else {
                    $this->values[$key] = $value;
                }
            }

            return $this;
        }

        /**
         * Returns value from set
         *
         * @param string|callable $key
         * @param mixed $default [optional]
         *
         * @return mixed
         */
        public function get($key, $default = null)
        {
            if (is_callable($key) && false === is_string($key)) {
                return $key($this, $default);
            }

            if ($this->delimiter && count($keys = explode($this->delimiter, $key, 2)) > 1) {
                $self = $this->get($keys[0]);
                if ($self instanceof Set) {
                    return $self->get($keys[1], $default);
                }
                return $default;
            }

            if (array_key_exists($key, $this->values)) {

                if (is_array($this->values[$key])) {
                    $this->values[$key] = new Set($this->values[$key], $this->delimiter);
                }

                return $this->values[$key];
            }

            return $default;
        }

        /**
         * Returns `true` if key exists
         *
         * @param string $key
         *
         * @return bool
         */
        public function has($key)
        {
            return $this !== $this->get($key, $this);
        }

        /**
         * Returns value and remove its from set
         *
         * @param string $key
         * @param mixed  $default [optional]
         *
         * @return mixed|null
         */
        public function pop($key, $default = null)
        {

        }

        /**
         * Returns all values
         *
         * @return array
         */
        public function all()
        {
            $values = [];
            foreach ($this->values as $key => $value) {
                $value = $this->get($key);
                if ($value instanceof Set) {
                    $value = $value->all();
                }
                $values[$key] = $value;
            }
            return $values;
        }

        /**
         * Clear all values
         *
         * @return \Hope\Util\Set
         */
        public function clear()
        {
            $this->values = [];
            return $this;
        }

        /**
         * Merge two Set values
         *
         * @param \Hope\Util\Set $set
         *
         * @return \Hope\Util\Set
         */
        public function merge(Set $set)
        {
            return $this->set($set->all());
        }

        /**
         * Returns Set values as array
         *
         * @return array
         */
        public function toArray()
        {
            return $this->all();
        }

        /**
         * Create new set instance
         *
         * @param array  $values    [optional]
         * @param string $separator [optional]
         */
        public function __construct(array $values = null, $separator = null)
        {
            if ($values) {
                foreach ($values as $key => $value) {
                    $this->set($key, $value);
                }
            }
            if (is_string($separator)) {
                $this->delimiter = $separator;
            }
        }

        /**
         * @inheritdoc
         */
        public function __set($key, $value)
        {
            $this->set($key, $value);
        }

        /**
         * @inheritdoc
         */
        public function __get($key)
        {
            return $this->get($key);
        }

        /**
         * @inheritdoc
         */
        public function __unset($key)
        {
            $this->pop($key);
        }

        /**
         * @inheritdoc
         */
        public function __isset($key)
        {
            return $this->has($key);
        }

        /**
         * @inheritdoc
         */
        public function offsetSet($key, $value)
        {
            return $this->set($key, $value);
        }

        /**
         * @inheritdoc
         */
        public function offsetGet($key)
        {
            return $this->get($key);
        }

        /**
         * @inheritdoc
         */
        public function offsetExists($key)
        {
            return $this->has($key);
        }

        /**
         * @inheritdoc
         */
        public function offsetUnset($key)
        {
            return $this->pop($key);
        }

        /**
         * @inheritdoc
         */
        public function getIterator()
        {
            return new ArrayIterator($this->values);
        }

    }

}