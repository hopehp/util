<?php

namespace Hope\Util\Queue
{

    /**
     * Class Sorted
     *
     * @package Hope\Util\Queue
     */
    class Sorted
    {

        protected $_sorted = true;

        protected $_values =    [];

        /**
         * Construct ordered queue
         *
         * @param int|callable $order [optional]
         */
        function __construct($order = SORT_ASC)
        {
        }

        /**
         * @param mixed $value
         * @param int   $priority
         *
         *
         * @return \Hope\Util\Queue\Sorted
         */
        public function insert($value, $priority)
        {
            $this->_values[] = [
                'value' => $value,
                'priority' => $priority
            ];
            $this->_sorted = false;

            return $this;
        }

        /**
         * Delete item from queue
         *
         * @param mixed $value
         *
         * @return bool
         */
        public function delete($value)
        {
            if (false === $this->isEmpty()) {
                foreach ($this->_values as $key => $data) {
                    if ($data['value'] === $value) {
                        unset($this->_values[$key]);
                        return true;
                    }
                }
            }
            return false;
        }

        /**
         * Returns queue values
         *
         * @return array
         */
        public function extract()
        {
            $this->sort();
            return array_map(function($item) {
                return $item['value'];
            }, $this->_values);
        }

        /**
         * Compare items priority
         *
         * @param int $priority1
         * @param int $priority2
         *
         * @return bool
         */
        public function compare($priority1, $priority2)
        {
            if ($priority1 === $priority2) return 0;
            return $priority1 > $priority2 ? -1 : 1;
        }

        /**
         * Clear queue
         *
         * @return void
         */
        public function clear()
        {
            $this->_values = [];
        }

        /**
         * Sort queue values
         *
         * @return \Hope\Util\Queue\Sorted
         */
        public function sort()
        {
            if (false === $this->isSorted()) {
                usort($this->_values, function($one, $two) {
                    return $this->compare($one['priority'], $two['priority']);
                });
                $this->_sorted = true;
            }
            return $this;
        }
        
        /**
         * Returns iterator for values
         *
         * @return \Generator
         */
        public function each()
        {
            $this->sort();
            foreach ($this->_values as $data) {
                yield $data['value'];
            }
        }

        /**
         * Returns `true` if queue is empty
         *
         * @return bool
         */
        public function isEmpty()
        {
            return count($this->_values) === 0;
        }

        /**
         * Returns `true` if queue already sorted
         *
         * @return bool
         */
        public function isSorted()
        {
            return $this->_sorted;
        }

    }

}