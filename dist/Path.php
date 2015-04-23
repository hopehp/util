<?php

namespace Hope\Util
{

    use Hope\Core\Error;

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
         * @throws \Hope\Core\Error
         *
         * @return \Hope\Util\Path
         */
        public function addLink($name, $path)
        {
            if (false === is_string($name)) {
                throw new Error('Path link name must be a string');
            }
            if (false === is_string($path)) {
                throw new Error('Path must be a string');
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
         * @throws \Hope\Core\Error
         *
         * @return \Hope\Util\Path
         */
        public function addMarker($name, $data)
        {
            if (false === is_string($name)) {
                throw new Error('Path marker must be a string');
            }
            if (false === is_string($data)) {
                throw new Error('Data must be a string');
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

            return join(DIRECTORY_SEPARATOR, $result);
        }

        /**
         * Resolve path and check existing
         *
         * @param string $path
         *
         * @throws \Hope\Core\Error
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
         * @throws \Hope\Core\Error
         *
         * @return string
         */
        public function replace($path)
        {
            if (false === is_string($path)) {
                throw new Error('Path must be a string');
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
                join(DIRECTORY_SEPARATOR, $path)
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
                join(DIRECTORY_SEPARATOR, $path)
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