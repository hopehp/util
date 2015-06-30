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