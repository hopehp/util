<?php

namespace Hope\Util
{

    use Countable;
    use ArrayAccess;

    use Hope\Core\Error;

    /**
     * Class Dict
     *
     * @package Hope\Util
     */
    class Dict implements Countable, ArrayAccess
    {

        /**
         * Dict values
         *
         * @var array
         */
        protected $_items = [];

        /**
         * Create Dict
         *
         * @param array $values
         *
         * @throws \Hope\Core\Error
         */
        public function __construct(array $values = [])
        {
            foreach ($values as $key => $value) {
                $this->set($key, $value);
            }
        }

        /**
         * Set new map item
         *
         * @param mixed $name
         * @param mixed $item
         *
         * @throws \Hope\Core\Error
         *
         * @return \Hope\Util\Dict
         */
        public function set($name, $item)
        {
            if (false === is_string($name) && false === is_object($name)) {
                throw new Error('Dict key name must be a string or object');
            }
            $this->_items[$name] = $item;

            return $this;
        }


        /**
         * Returns map value
         *
         * @param mixed $name
         * @param bool  $default [optional] Default value that returned if key not existing
         *
         * @return bool|mixed
         */
        public function get($name, $default = false)
        {
            if (isset($this->_items[$name])) {
                return $this->_items[$name];
            }
            return $default;
        }

        /**
         * Returns all map values
         *
         * @return array
         */
        public function all()
        {
            return $this->_items;
        }

        /**
         * Returns map key list
         *
         * @return array
         */
        public function keys()
        {
            return array_keys($this->_items);
        }

        /**
         * Sort map items
         *
         * @param callable $callable
         *
         * @return \Hope\Util\Dict
         */
        public function sort(callable $callable)
        {
            uksort($this->_items, $callable);

            return $this;
        }

        /**
         * Fill dict items
         *
         * @param \Hope\Util\Dict|array $values
         *
         * @throws \Hope\Core\Error
         *
         * @return \Hope\Util\Dict
         */
        public function fill($values)
        {
            if ($values instanceof Dict) {
                $values = $values->all();
            }

            foreach($values as $key => $value) {
                if (false === array_key_exists($key, $this->_items)) {
                    $this->set($key, $value);
                }
            }

            return $this;
        }

        /**
         * Find key name by value
         *
         * @param mixed $value
         *
         * @return mixed
         */
        public function find($value)
        {
            return array_search($value, $this->_items);
        }

        /**
         * Each map values
         *
         * @param callable $handler
         *
         * @return \Hope\Util\Dict
         */
        public function each(callable $handler)
        {
            foreach($this->all() as $key => $value) {
                call_user_func($handler, $key, $value);
            }
            return $this;
        }

        /**
         * Returns map size
         *
         * @return int
         */
        public function count()
        {
            return count($this->_items);
        }

        /**
         * Clear map
         *
         * @return \Hope\Util\Dict
         */
        public function clear()
        {
            $this->_items = [];
            return $this;
        }

        /**
         * Check if key exists
         *
         * @param mixed $name
         *
         * @return bool
         */
        public function exists($name)
        {
            return isset($this->_items[$name]);
        }

        /**
         * Remove item from map by key value if exists
         *
         * @param mixed $name
         * @param bool  $throw [optional]
         *
         * @return \Hope\Util\Dict
         */
        public function remove($name, $throw = true)
        {
            if (isset($this->_items[$name])) {
                unset($this->_items[$name]);
            } else if ($throw) {
                throw new \InvalidArgumentException('The map has no key named "' . $name . '"');
            }
            return $this;
        }

        /**
         * Filter current map items and return new Dict instance
         *
         * @param callable $filter
         *
         * @return \Hope\Util\Dict
         */
        public function filter(callable $filter)
        {
            return new static(array_filter($this->_items, $filter));
        }

        /**
         * Concat map values
         *
         * @param string|callable $delimiter
         *
         * @return string
         */
        public function concat($delimiter)
        {
            $result = '';

            if (is_string($delimiter)) {
                $result = implode($delimiter, $this->all());
            } else if (is_callable($delimiter)) {
                foreach ($this->all() as $key => $value) {
                    $result = call_user_func($delimiter, $result, $value, $key);
                }
            }
            return $result;
        }

        /**
         * Merge two maps
         *
         * @param \Hope\Util\Dict $map
         * @param bool           $recursive [optional]
         *
         * @return \Hope\Util\Dict
         */
        public function merge(Dict $map, $recursive = false)
        {
            $this->_items = $recursive
                ? array_merge_recursive($this->_items, $map->all())
                : array_merge($this->_items, $map->all())
            ;
            return $this;
        }

        /**
         * Returns first item from map
         *
         * @return mixed|null
         */
        public function first()
        {
            return reset($this->_items);
        }

        /**
         * Returns the last item from map
         *
         * @return mixed|null
         */
        public function last()
        {
            return end($this->_items);
        }

        /**
         * Return new copy of this Dict instance
         *
         * @return \Hope\Util\Dict
         */
        public function copy()
        {
            return new static($this->all());
        }

        /**
         * {@inheritdoc}
         */
        public function offsetExists($offset)
        {
            return $this->exists($offset);
        }

        /**
         * {@inheritdoc}
         */
        public function offsetGet($offset)
        {
            return $this->get($offset);
        }

        /**
         * {@inheritdoc}
         */
        public function offsetSet($offset, $value)
        {
            $this->set($offset, $value);
        }

        /**
         * {@inheritdoc}
         */
        public function offsetUnset($offset)
        {
            $this->remove($offset);
        }

    }

}