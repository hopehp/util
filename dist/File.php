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
         * @param string $path [optional]
         */
        public function __construct($path = null)
        {
            if ($path) {
                $this->setPath($path);
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
            if ($this->isUploaded()) {
                $result = move_uploaded_file($this->_path, $to);
            } else if ($result = rename($this->_path, $to)) {
                $this->_path = $to;
            }
            if ($result) {
                $this->setPath($to);
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
         * Set new file path
         *
         * @param string $path
         *
         * @return \Hope\Util\File
         */
        public function setPath($path)
        {
            $this->_path = $path;
            // Detect information
            if ($info = pathinfo($path)) {
                $this->_name = $info['filename'];
                $this->_ext = $info['extension'];
            }

            return $this;
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
         * Checks if path is uploaded
         *
         * @return bool
         */
        public function isUploaded()
        {
            return is_uploaded_file($this->_path);
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
            if ($this->_mime === null) {
                // TODO : Add checking by content
                $this->_mime = Mime::getMime($this->_ext);
            }
            return $this->_mime;
        }

        /**
         * Returns file data
         *
         * @return string|null
         */
        public function getData()
        {
            if ($this->_data === null && $this->exists() && $this->isReadable()) {
                $this->_data = file_get_contents($this->_path);
            }
            return $this->_data;
        }

    }

}