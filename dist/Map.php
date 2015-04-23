<?php

namespace Hope\Util
{
    use Countable;
    use Hope\Core\Error;

    /**
     * Class Map
     *
     * @package Hope\Util
     */
    class Map implements Countable
    {

        /**
         * Map values
         *
         * @var array
         */
        protected $_items = [];

        /**
         * Create Map
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
         * @return \Hope\Util\Map
         */
        public function set($name, $item)
        {
            if (false === is_string($name) && false === is_object($name)) {
                throw new Error('Map key name must be a string or object');
            }
            $this->_items[$name] = $item;

            return $this;
        }


        /**
         * Returns map value
         *
         * @param mixed $name
         *
         * @return mixed|bool
         */
        public function get($name)
        {
            if (isset($this->_items[$name])) {
                return $this->_items[$name];
            }
            return false;
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
         * @return \Hope\Util\Map
         */
        public function sort(callable $callable)
        {
            usort($this->_items, $callable);
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
         * @return \Generator
         */
        public function each()
        {
            foreach($this->all() as $item) {
                yield $item;
            }
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
         * @return \Hope\Util\Map
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
         *
         * @return \Hope\Util\Map
         */
        public function remove($name)
        {
            if (isset($this->_items[$name])) {
                unset($this->_items[$name]);
            } else {
                throw new \InvalidArgumentException('The map has no key named "' . $name . '"');
            }
            return $this;
        }

        /**
         * Filter current map items and return new Map instance
         *
         * @param callable $filter
         *
         * @return \Hope\Util\Map
         */
        public function filter(callable $filter)
        {
            return new static(array_filter($this->_items, $filter));
        }

        /**
         * Returns first item from map
         *
         * @return null
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
         * Return new copy of this Map instance
         *
         * @return \Hope\Util\Map
         */
        public function copy()
        {
            return new static($this->all());
        }
    }

}