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
namespace Hope\Util\Date
{

    use DateInterval;

    /**
     * Class Interval
     *
     * @package Hope\Util\Date
     * @property-read int $minutes Interval minutes
     * @property-read int $months Interval months
     * @property-read int $seconds Interval seconds
     * @property-read int $years Interval years
     * @property-read int $hours Interval hours
     */
    class Interval extends DateInterval
    {

        /**
         * @var array
         */
        public static $aliases = [
            'months' => 'm',
            'hours' => 'h',
            'years' => 'y',
            'minutes' => 'i',
            'seconds' => 's',
        ];


        /**
         * @var DateInterval
         */
        protected $_original;

        /**
         * @param string $interval
         */
        public function __construct($interval = null)
        {
            if ($interval instanceof DateInterval) {
                $this->_original = $interval;
            } elseif ($interval !== null) {
                if (preg_match('/\s+/', $interval)) {
                    $this->_original = DateInterval::createFromDateString($interval);
                } else {
                    $this->_original = new DateInterval($interval);
                }
            } else {
                $this->_original = new DateInterval('P0Y0M0DT0H0M0S');
            }
        }


        /**
         * Returns interval parts by aliases
         *
         * @param string $alias
         *
         * @return mixed
         */
        public function __get($alias)
        {
            if (isset(static::$aliases[$alias])) {
                return $this->_original->{static::$aliases[$alias]};
            }
            return $this->_original->{$alias};
        }

        public function __set($name, $value)
        {
            $this->_original->{$name} = $value;
        }

        /**
         * @param int $sec
         *
         * @return \Hope\Util\Date\Interval
         */
        public function add($sec)
        {
            return $this;
        }

        /**
         * Add year to interval
         *
         * @param int $y
         *
         * @return \Hope\Util\Date\Interval
         */
        public function addYear($y)
        {
            $this->_original->y += (int) $y;

            return $this;
        }

        /**
         * Add month to interval
         *
         * @param int $m
         *
         * @return \Hope\Util\Date\Interval
         */
        public function addMonth($m)
        {
            $this->_original->m += (int) $m;

            return $this;
        }

        /**
         * Add month to interval
         *
         * @param int $w
         *
         * @return \Hope\Util\Date\Interval
         */
        public function addWeek($w)
        {
            $this->_original->d += (int) $w * 7;

            return $this;
        }

        /**
         * Add days to interval
         *
         * @param int $d
         *
         * @return \Hope\Util\Date\Interval
         */
        public function addDay($d = 1)
        {
            $this->_original->d += (int) $d;

            return $this;
        }

        /**
         * Add hours to interval
         *
         * @param int $h
         *
         * @return Interval
         */
        public function addHour($h)
        {
            $this->_original->h += (int) $h;

            return $this;
        }

        /**
         * Add minutes to interval
         *
         * @param int $m
         *
         * @return Interval
         */
        public function addMinute($m)
        {
            $this->_original->i += (int) $m;

            return $this;
        }

        /**
         * Add seconds to interval
         *
         * @param int $s
         *
         * @return Interval
         */
        public function addSecond($s)
        {
            $this->_original->s += (int) $s;

            return $this;
        }

        /**
         * Returns true if interval is negative
         *
         * @return bool
         */
        public function isInverted()
        {
            return (bool) $this->_original->invert;
        }

        public function getOriginal()
        {
            return $this->_original;
        }

        /**
         * Reverse interval
         *
         * @return \Hope\Util\Date\Interval
         */
        public function reverse()
        {
            $this->_original->invert = (int) !$this->_original->invert;
            return $this;
        }

        /**
         * Intervals equal
         *
         * @param \Hope\Util\Date\Interval $interval
         *
         * @return bool
         */
        public function eq(Interval $interval)
        {
            return $this == $interval;
        }

        /**
         * Intervals not equal
         *
         * @param \Hope\Util\Date\Interval $interval
         *
         * @return bool
         */
        public function neq(Interval $interval)
        {
            return $this != $interval;
        }

        /**
         * Current interval greatest
         *
         * @param \Hope\Util\Date\Interval $interval
         *
         * @return bool
         */
        public function gt(Interval $interval)
        {
            return $this > $interval;
        }

        /**
         * Current interval greatest or equal
         *
         * @param \Hope\Util\Date\Interval $interval
         *
         * @return bool
         */
        public function gte(Interval $interval)
        {
            return $this >= $interval;
        }

        /**
         * Current interval less
         *
         * @param \Hope\Util\Date\Interval $interval
         *
         * @return bool
         */
        public function lt(Interval $interval)
        {
            return $this < $interval;
        }

        /**
         * Current interval less or equal
         *
         * @param \Hope\Util\Date\Interval $interval
         *
         * @return bool
         */
        public function lte(Interval $interval)
        {
            return $this <= $interval;
        }

        /**
         * Create new Interval instance
         *
         * @return \Hope\Util\Date\Interval
         */
        public static function make()
        {
            return new static();
        }

        /**
         * Wrap native DateInterval
         *
         * @param \DateInterval $di
         *
         * @return \Hope\Util\Date\Interval
         */
        public static function wrap(DateInterval $di)
        {
            if ($di instanceof Interval) {
                return $di;
            }
            return new static($di);

//            var_dump($di); die;
//            $interval->invert = $di->invert;
//            $interval->days = $di->days;
////            foreach ( as $item) {
////
////            }
//
////            $interval->invert = $di->invert;
////            $interval->addDay($di->days);
////            var_dump($interval->days, $di->days);die;
////            $interval->days = $di->days;
////            $interval->y = $di->y;
////            $interval->m = $di->m;
////            $interval->d = $di->d;
////            $interval->h = $di->h;
////            $interval->i = $di->i;
////            $interval->s = $di->s;
//
////            var_dump($interval);
            return $interval;
        }

    }

}