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
     * Class Queue
     *
     * @package Hope\Util
     */
    class Queue
    {

        /**
         * Queue items
         *
         * @var mixed[]
         */
        protected $_items;

        /**
         * Return queue item
         *
         * @param int $index
         *
         * @return mixed
         */
        public function get($index)
        {
            if (isset($this->_items[$index])) {
                return $this->_items[$index];
            }
            // TODO : Replace by Error
            return null;
        }

        /**
         * Pop item from queue
         *
         * @return mixed
         */
        public function pop()
        {
            return array_pop($this->_items);
        }

        /**
         * @param $value
         *
         * @return mixed
         */
        public function find($value)
        {
            return array_search($value, $this->_items);
        }

        /**
         * Shift item from queue
         *
         * @return mixed
         */
        public function shift()
        {
            return array_shift($this->_items);
        }

        /**
         * Returns queue items count
         *
         * @return int
         */
        public function count()
        {
            return count($this->_items);
        }

        /**
         * Return queue values
         *
         * @return array
         */
        public function values()
        {
            return array_values($this->_items);
        }

        /**
         * Insert item to queue
         *
         * @param mixed $value
         *
         * @return \Hope\Util\Queue
         */
        public function insert($value)
        {
            $this->_items[] = $value;
            return $this;
        }

        /**
         * Update queue value by index
         *
         * @param int   $index
         * @param mixed $value
         *
         * @return \Hope\Util\Queue
         */
        public function update($index, $value)
        {
            if (isset($this->_items[$index])) {
                $this->_items[$index] = $value;
            }
            return $this;
        }

        /**
         * Check if index exists in queue
         *
         * @param int $index
         *
         * @return bool
         */
        public function exists($index)
        {
            return isset($this->_items[$index]);
        }

        /**
         * Remove item from queue by index
         *
         * @param int $index
         *
         * @return \Hope\Util\Queue
         */
        public function remove($index)
        {
            if (isset($this->_items[$index])) {
                unset($this->_items[$index]);
                // Reindex ;)
                $this->_items = array_values($this->_items);
            }
            return $this;
        }

        /**
         * Filter values in this queue
         *
         * @param callable $filter
         *
         * @return \Hope\Util\Queue
         */
        public function filter(callable $filter)
        {
            $this->_items = array_filter($this->_items, $filter);
            return $this;
        }

    }

}