<?php

namespace Hope\Util
{

    /**
     * Class File
     *
     * @package Hope\Util
     */
    class File
    {

        /**
         * Absolute path
         *
         * @var string
         */
        protected $_path;

        /**
         * Filename
         *
         * @var string
         */
        protected $_name;

        /**
         * File content
         *
         * @var string
         */
        protected $_data;

        /**
         * Mime type
         *
         * @var string
         */
        protected $_mime;

        /**
         * File extension
         *
         * @var string
         */
        protected $_ext;

        /**
         * Construct file instance
         *
         * @param string $path
         */
        public function __construct($path)
        {
            if (file_exists($path)) {
                $this->_path = $path;

                // Detect information
                if ($info = pathinfo($path)) {
                    $this->_name = $info['filename'];
                    $this->_ext = $info['extension'];
                }
            }
        }

        /**
         * Check if file exists
         *
         * @return bool
         */
        public function exists()
        {
            if ($this->_path) {
                return is_dir($this->_path) || is_file($this->_path) || is_link($this->_path);
            }
            return false;
        }

        /**
         * Copy source file and return new File instance
         *
         * @param string $to New path
         *
         * @return \Hope\Util\File|bool
         */
        public function copy($to)
        {
            if (copy($this->_path, $to)) {
                return new static($to);
            }
            return false;
        }

        /**
         * Move file to the new location
         *
         * @param string $to New path
         *
         * @return \Hope\Util\File
         */
        public function move($to)
        {
            if ($result = rename($this->_path, $to)) {
                $this->_path = $to;
            }
            return $result;
        }

        /**
         * Saves file
         *
         * @param string $to [optional] Destination path
         *
         * @return bool
         */
        public function save($to = null)
        {

        }

        /**
         * Returns file path
         *
         * @return string
         */
        public function getPath()
        {
            return $this->_path;
        }

        /**
         * Checks if path is file
         *
         * @return bool
         */
        public function isFile()
        {
            return is_file($this->_path);
        }

        /**
         * Checks if path is symbolic link
         *
         * @return bool
         */
        public function isLink()
        {
            return is_link($this->_path);
        }

        /**
         * Checks if path is a directory
         *
         * @return bool
         */
        public function isDirectory()
        {
            return is_dir($this->_path);
        }

        /**
         * Returns if path is executable file
         *
         * @return bool
         */
        public function isExecutable()
        {
            return is_executable($this->_path);
        }

        /**
         * Checks if path is readable
         *
         * @return bool
         */
        public function isReadable()
        {
            return is_readable($this->_path);
        }

        /**
         * Checks if path is writable
         *
         * @return bool
         */
        public function isWritable()
        {
            return is_writable($this->_path);
        }

        /**
         * Returns file mime type
         *
         * @param bool $check
         *
         * @return mixed
         */
        public function getMime($check = false)
        {
            if (null === $this->_mime) {
                $this->_mime;
            }
            return $this->_mime;
        }

    }

}