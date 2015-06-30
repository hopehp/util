<?php

namespace Hope\Util
{

    /**
     * Class Stream
     *
     * @package Hope\Util
     */
    class Stream
    {

        /**
         * Stream modes
         *
         * @var array
         */
        protected static $modes = [
            'read' => [
                'r' => true, 'w+' => true, 'r+' => true, 'x+' => true, 'c+' => true,
                'rb' => true, 'w+b' => true, 'r+b' => true, 'x+b' => true,
                'c+b' => true, 'rt' => true, 'w+t' => true, 'r+t' => true,
                'x+t' => true, 'c+t' => true, 'a+' => true
            ],
            'write' => [
                'w' => true, 'w+' => true, 'rw' => true, 'r+' => true, 'x+' => true,
                'c+' => true, 'wb' => true, 'w+b' => true, 'r+b' => true,
                'x+b' => true, 'c+b' => true, 'w+t' => true, 'r+t' => true,
                'x+t' => true, 'c+t' => true, 'a' => true, 'a+' => true
            ]
        ];

        /**
         * @var resource
         */
        protected $resource;

        /**
         * @var bool
         */
        protected $attached;

        /**
         * @var bool
         */
        protected $readable;

        /**
         * @var bool
         */
        protected $writable;

        /**
         * @var bool
         */
        protected $seekable;

        /**
         * Stream size
         *
         * @var int
         */
        protected $bufferSize = 4096;

        /**
         * Instantiate stream
         *
         * @param        $resource
         * @param string $mode
         *
         * @throws \InvalidArgumentException
         */
        public function __construct($resource, $mode = 'r+')
        {
            if (is_string($resource) && false === $resource = @fopen($resource, $mode)) {
                throw new \InvalidArgumentException('\Can\'t open resource from given link');
            }

            $this->attach($resource);
        }

        /**
         * Destruct stream
         */
        public function __destruct()
        {
            if ($this->resource) {
                fclose($this->resource);
            }
        }

        /**
         * Check EOF (End Of File)
         *
         * @return bool
         */
        public function eof()
        {
            if ($this->attached) {
                return feof($this->resource);
            }
            return true;
        }

        /**
         * Returns stream pointer position
         *
         * @throws \RuntimeException
         *
         * @return int
         */
        public function tell()
        {
            $this->assertSeekable();

            if (false === $result = ftell($this->resource)) {
                throw new \RuntimeException('Can\'t get stream offset');
            }
            return $result;
        }

        /**
         * Move stream point to a new position
         *
         * @param int $offset
         * @param int $whence
         *              - SEEK_SET - Set position equal to $offset bytes.
         *              - SEEK_CUR - Set position to current location plus $offset.
         *              - SEEK_END - Set position to end-of-file plus $offset.
         *
         * @throws \RuntimeException
         *
         * @return int
         */
        public function seek($offset, $whence = SEEK_SET)
        {
            $this->assertSeekable();

            if (false === $result = fseek($this->resource, $offset, $whence)) {
                throw new \RuntimeException('Cannot seek on stream');
            }

            return $result;
        }

        /**
         * Copy content of current stream to another stream
         *
         * @param \Hope\Util\Stream $stream
         *
         * @return int
         */
        public function pipe(Stream $stream)
        {
            return stream_copy_to_stream($this->getResource(), $stream->getResource());
        }

        /**
         * @param int $length [optional]
         *
         * @throws \RuntimeException
         *
         * @return string
         */
        public function read($length = null)
        {
            $this->assertReadable();

            if ($length === null) {
                $length = $this->bufferSize;
            }

            if (false === $result = fread($this->resource, $length)) {
                throw new \RuntimeException('Can\'t read stream');
            }

            return $result;
        }

        /**
         * Read line from stream
         *
         * @param int    $length
         * @param string $ending
         *
         * @throws \RuntimeException
         *
         * @return string
         */
        public function readLine($length = null, $ending = PHP_EOL)
        {
            $this->assertReadable();

            if ($length === null) {
                $length = $this->bufferSize;
            }

            if (false === $result = stream_get_line($this->resource, $length, $ending)) {
                throw new \RuntimeException('Can\'t read line from a stream');
            }
            return $result;
        }
        
        /**
         * @param string $value
         * @param int    $length [optional]
         *
         * @throws \RuntimeException
         *
         * @return int
         */
        public function write($value, $length = null)
        {
            $this->assertWritable();

            if ($length === null) {
                $result = fwrite($this->resource, $value);
            } else {
                $result = fwrite($this->resource, $value, $length);
            }

            if ($result === false) {
                throw new \RuntimeException('Can\'t write to stream');
            }

            return $result;
        }

        /**
         * Move the stream pointer to the beginning
         *
         * @throws \RuntimeException
         *
         * @return bool
         */
        public function rewind()
        {
            return false !== $this->seek(0);
        }

        /**
         * Attach new resource to a stream
         *
         * @param resource $resource
         *
         * @throws \InvalidArgumentException
         *
         * @return Stream
         */
        public function attach($resource)
        {
            if (false === is_resource($resource)) {
                throw new \InvalidArgumentException('Stream resource must be a resource');
            }
            $this->resource = $resource;
            $this->attached = true;

            return $this;
        }

        /**
         * Detach resource from stream
         *
         * @return resource
         */
        public function detach()
        {
            $res = $this->resource;

            // Clear data
            $this->resource = $this->readable = $this->writable = null;
            $this->attached = false;

            return $res;
        }

        /**
         * Close stream
         *
         * @return bool
         */
        public function close()
        {
            if (false === $this->isOpen()) {
                return true;
            }
            if ($result = fclose($this->resource)) {
                $this->detach();
            }

            return $result;
        }

        /**
         * Returns true if resource is attached
         *
         * @return bool
         */
        public function isOpen()
        {
            return $this->attached;
        }

        /**
         * Returns true if resource is local
         *
         * @return bool
         */
        public function isLocal()
        {
            if ($this->attached) {
                return stream_is_local($this->resource);
            }
            return false;
        }

        /**
         * Checks resource mode
         *
         * @return bool
         */
        public function isReadable()
        {
            if ($this->attached && $this->readable === null) {
                $this->readable = array_key_exists($this->getMetadata('mode'), static::$modes['read']);
            }
            return $this->readable;
        }

        /**
         * Checks resource mode
         *
         * @return bool
         */
        public function isWritable()
        {
            if ($this->attached && $this->writable === null) {
                $this->writable = array_key_exists($this->getMetadata('mode'), static::$modes['write']);
            }
            return $this->writable;
        }

        /**
         * Checks resource mode
         *
         * @return bool
         */
        public function isSeekable()
        {
            if ($this->attached && $this->seekable === null) {
                $this->seekable = (bool) $this->getMetadata('seekable');
            }
            return $this->seekable;
        }

        /**
         * Returns stream metadata
         *
         * @param string $name [optional]
         *
         * @return array|mixed
         */
        public function getMetadata($name = null)
        {
            if (false === $this->attached) {
                return null;
            }
            $meta = stream_get_meta_data($this->resource);

            if ($name) {
                return isset($meta[$name])
                    ? $meta[$name]
                    : null;
            }
            return $meta;
        }

        /**
         * Returns stream content from current point position
         *
         * For getting all stream data call Stream::rewind() before this method
         *
         * @throws \RuntimeException
         *
         * @return string
         */
        public function getContents()
        {
            $this->assertReadable();
            return stream_get_contents($this->resource);
        }

        /**
         * Returns stream resource
         *
         * @return resource
         */
        public function getResource()
        {
            return $this->resource;
        }

        /**
         * Set stream buffer size
         *
         * @param int $size
         *
         * @return static
         */
        public function setBuffetSize($size)
        {
            $this->bufferSize = (int) $size;
            return $this;
        }

        /**
         * Returns stream buffer size
         *
         * @return int
         */
        public function getBufferSize()
        {
            return $this->bufferSize;
        }

        /**
         * Assert readable
         *
         * @throws \RuntimeException
         */
        protected function assertReadable()
        {
            if (false === $this->isOpen()) {
                throw new \RuntimeException('Can\'t read from a closed stream');
            }
            if (false === $this->isReadable()) {
                throw new \RuntimeException(sprintf(
                    'Can\'t read on a non readable stream with mode %s', $this->getMetadata('mode')
                ));
            }
        }

        /**
         * Assert writable
         *
         * @throws \RuntimeException
         */
        protected function assertWritable()
        {
            if (false === $this->isOpen()) {
                throw new \RuntimeException('Can\'t write to a closed stream');
            }
            if (false === $this->isSeekable()) {
                throw new \RuntimeException(sprintf(
                    'Can\'t write to a non writable stream with mode %s', $this->getMetadata('mode')
                ));
            }
        }

        /**
         * @throws \RuntimeException
         */
        protected function assertSeekable()
        {
            if (false === $this->isOpen()) {
                throw new \RuntimeException('Can\'t seek on a closed stream');
            }
            if (false === $this->isReadable()) {
                throw new \RuntimeException('Can\'t seek on a non seekable stream');
            }
        }

    }

}