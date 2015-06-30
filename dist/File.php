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
         * @throws \RuntimeException
         *
         * @return bool
         */
        public function save($to = null)
        {
            if ($this->isUploaded()) {
                if (false === is_string($to)) {
                    throw new \RuntimeException('For saving uploaded files needed destination path');
                }
                $result = move_uploaded_file($this->getPath(), $to);
            } else {
                $result = file_put_contents($to ? : $this->getPath(), $this->_data);
            }

            if ($result && $to) {
                $this->setPath($to);
            }

            return $result;
        }

        /**
         * Set file name
         *
         * @param string $name
         *
         * @return \Hope\Util\File
         */
        public function setName($name)
        {
            if ($info = pathinfo($name)) {
                // TODO : Validate empty file name?
                $this->_name = $info['filename'];

                // Set file extension
                if (isset($info['extension'])) {
                    $this->_ext = $info['extension'];
                }
            }

            return $this;
        }

        /**
         * Returns file name
         *
         * @return string
         */
        public function getName()
        {
            return $this->_name;
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

            // Detect name and extension from path
            $this->setName($path);

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
                $this->_mime = $check
                    ? Mime::detect($this->getData())
                    : Mime::getMime($this->_ext);
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