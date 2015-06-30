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
     * Class Path
     *
     * @package Hope\Util
     */
    class Path
    {

        protected $_home;

        /**
         * Path links
         *
         * @var array
         */
        protected $_links = [];

        /**
         * Path markers
         *
         * @var array
         */
        protected $_marks = [];

        /**
         * Setup home path
         *
         * @param string $path
         *
         * @return \Hope\Util\Path
         */
        public function setHome($path)
        {
            $this->_home = $path;
            return $this;
        }

        /**
         * Returns HOME(~) path
         *
         * @return string
         */
        public function getHome()
        {
            return $this->_home;
        }

        /**
         * Register path link
         *
         * @param string $name Link name
         * @param string $path Link path
         *
         * @throws \InvalidArgumentException
         *
         * @return \Hope\Util\Path
         */
        public function addLink($name, $path)
        {
            if (false === is_string($name)) {
                throw new \InvalidArgumentException('Path link name must be a string');
            }
            if (false === is_string($path)) {
                throw new \InvalidArgumentException('Path must be a string');
            }
            $this->_links[$name] = $path;

            return $this;
        }

        /**
         * Register math marker
         *
         * @param string $name Marker name
         * @param string $data Marker value
         *
         * @throws \InvalidArgumentException
         *
         * @return \Hope\Util\Path
         */
        public function addMarker($name, $data)
        {
            if (false === is_string($name)) {
                throw new \InvalidArgumentException('Path marker must be a string');
            }
            if (false === is_string($data)) {
                throw new \InvalidArgumentException('Data must be a string');
            }
            $this->_marks[$name] = $data;

            return $this;
        }

        /**
         * Normalize path
         *
         * @param string $path
         *
         * @return string
         */
        public function normalize($path)
        {
            $parts = preg_split('|[\\\/]|', $path);
            $result = [];

            // TODO: This is need?
            if ($parts && $parts[0] === '~') {
                $parts[0] = $this->getHome();
            }

            foreach ($parts as $part) {
                switch ($part) {
                    case '..':
                        array_pop($result);
                        break;
                    case '.':
                        // Skip this element
                        break;
                    default:
                        $result[] = $part;
                        break;
                }
            }

            return implode(DIRECTORY_SEPARATOR, $result);
        }

        /**
         * Resolve path and check existing
         *
         * @param string $path
         *
         * @throws \InvalidArgumentException
         *
         * @return bool|string
         */
        public function resolve($path)
        {
            $path = $this->replace($path);

            if (is_file($path) or is_dir($path)) {
                return $path;
            }
            return false;
        }

        /**
         * Replace path links and markers
         *
         * @param string $path Path pattern
         *
         * @throws \InvalidArgumentException
         *
         * @return string
         */
        public function replace($path)
        {
            if (false === is_string($path)) {
                throw new \InvalidArgumentException('Path must be a string');
            }

            $path = preg_replace_callback('|^@([\w]+)|', function($v) {
                return isset($this->_links[$v[1]]) ? $this->_links[$v[1]] : $v[0];
            }, $path);

            return $path;
        }

        /**
         * Join parts and normalize resulted path
         *
         * @param string ...$path
         *
         * @return string
         */
        public function join(...$path)
        {
            return $this->normalize(
                implode(DIRECTORY_SEPARATOR, $path)
            );
        }

        /**
         * Ensure path existing
         *
         * @param string ...$path
         *
         * @return bool
         */
        public function make(...$path)
        {
            $path = $this->normalize(
                implode(DIRECTORY_SEPARATOR, $path)
            );

            if (false === file_exists($path)) {
                return mkdir($path, '0777', true);
            }

            return true;
        }

        /**
         * Instantiate path util
         */
        public function __construct()
        {
            $this->setHome(isset($_SERVER['HOME']) ? $_SERVER['HOME'] : getcwd());
        }
    }

}