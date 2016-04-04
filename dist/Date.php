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

    use DateTime;
    use DateTimeZone;
    use Hope\Util\Date\Parts;
    use Hope\Util\Date\Period;
    use Hope\Util\Date\Interval;

    /**
     * Class Date
     *
     * @package Hope\Util
     */
    class Date extends DateTime
    {

        /**
         * The month constants
         */
        const JANUARY   = 1;
        const FEBRUARY  = 2;
        const MARCH     = 3;
        const APRIL     = 4;
        const MAY       = 5;
        const JUNE      = 6;
        const JULE      = 7;
        const AUGUST    = 8;
        const SEPTEMBER = 9;
        const OCTOBER   = 10;
        const NOVEMBER  = 11;
        const DECEMBER  = 12;

        /**
         * The day constants
         */
        const SUNDAY    = 7;
        const MONDAY    = 1;
        const TUESDAY   = 2;
        const WEDNESDAY = 3;
        const THURSDAY  = 4;
        const FRIDAY    = 5;
        const SATURDAY  = 6;

        /**
         * The time stamp constants
         */
        const WEEK      = 604800;
        const DAY       = 86400;
        const HOUR      = 3600;
        const MINUTE    = 60;

        
        /**
         * Create Date instance
         *
         * @param string        $time
         * @param \DateTimeZone $timezone
         */
        public function __construct($time = 'now', DateTimeZone $timezone = null)
        {
            parent::__construct($time, $timezone);
        }

        /**
         * Create date period to given date
         *
         * @param \Hope\Util\Date          $date
         * @param \Hope\Util\Date\Interval $interval
         *
         * @return \Hope\Util\Date\Period
         */
        public function to(Date $date, Interval $interval = null)
        {
            return new Period($this, $date, $interval);
        }

        /**
         * Add time to date
         *
         * @param \DateInterval|int $interval
         *
         * @return \Hope\Util\Date
         */
        public function add($interval)
        {
            if ($interval instanceof \DateInterval) {
                parent::add($interval);
            } else if (is_integer($interval)) {
                $this->setTimestamp($this->getTimestamp() + $interval);
            }

            return $this;
        }

        /**
         * Sub time from date
         *
         * @param \DateInterval|int $interval
         *
         * @return \Hope\Util\Date
         */
        public function sub($interval)
        {
            if ($interval instanceof \DateInterval) {
                parent::sub($interval);
            } else if (is_integer($interval)) {
                $this->setTimestamp($this->getTimestamp() - $interval);
            }

            return $this;
        }

        /**
         * @inheritdoc
         *
         * @param DateTime $datetime2
         * @param bool     $absolute
         *
         * @return Interval|bool
         */
        public function diff($datetime2, $absolute = false)
        {
            $period = parent::diff($datetime2, $absolute);

            if ($period) {
                $period = Interval::wrap($period);
            }

            return $period;
        }

        /**
         * Copy this Date instance
         *
         * @return \Hope\Util\Date
         */
        public function copy()
        {
            return new static($this->getTimestamp());
        }

        /**
         * Equal
         *
         * @param \DateTime $dt
         *
         * @return bool
         */
        public function eq(DateTime $dt)
        {
            return $this == $dt;
        }

        /**
         * Not equal
         *
         * @param \DateTime $dt
         *
         * @return bool
         */
        public function neq(DateTime $dt)
        {
            return $this != $dt;
        }

        /**
         * Greatest
         *
         * @param \DateTime $dt
         *
         * @return bool
         */
        public function gt(DateTime $dt)
        {
            return $this > $dt;
        }

        /**
         * Greatest or equal
         *
         * @param \DateTime $dt
         *
         * @return bool
         */
        public function gte(DateTime $dt)
        {
            return $this >= $dt;
        }

        /**
         * Less than this
         *
         * @param \DateTime $dt
         *
         * @return bool
         */
        public function lt(DateTime $dt)
        {
            return $this < $dt;
        }

        /**
         * Less or equal than this
         *
         * @param \DateTime $dt
         *
         * @return bool
         */
        public function lte(DateTime $dt)
        {
            return $this <= $dt;
        }

        /**
         * Check if date inside two dates
         *
         * @param \Hope\Util\Date $from
         * @param \Hope\Util\Date $to
         *
         * @return bool
         */
        public function between(Date $from, Date $to)
        {
            return $this->gte($from) && $this->lte($to);
        }

        /**
         * Another method for checking if date inside two dates
         *
         * @param \Hope\Util\Date\Period $period
         *
         * @return bool
         */
        public function inside(Date\Period $period)
        {
            return $this->between($period->getStart(), $period->getEnd());
        }


        public function setYear($number)
        {
            return $this->setDate((int) $number, $this->getMonth(), $this->getDay());
        }


        /**
         * Returns hours
         *
         * @return int
         */
        public function getHours()
        {
            return $this->get('hours');
        }
        
        
        /**
         * Returns month day number start with 1
         *
         * @return int
         */
        public function getDay()
        {
            return (int) $this->format('j');
        }

        /**
         * Returns week number in year
         *
         * @return int
         */
        public function getWeek()
        {
            return (int) $this->format('W');
        }

        public function getMonth()
        {
            return (int) $this->format('n');
        }

        /**
         * Returns day of week
         *
         * @return int
         */
        public function getWeekDay()
        {
            return (int) $this->format('N');
        }

        /**
         * Returns year number
         *
         * @return int
         */
        public function getYear()
        {
            // TODO: Check ISO-8601 standard
            return (int) $this->format('Y');
        }

        /**
         * Returns day of year starts with 1 to 366
         *
         * @return int
         */
        public function getYearDay()
        {
            return (int) $this->format('z') + 1;
        }

        /**
         * Returns true if year is leap
         *
         * @return bool
         */
        public function isLeapYear()
        {
            return (bool) $this->format('L');
        }

        /**
         * Get date part
         *
         * @param string $part
         *
         * @throws \InvalidArgumentException
         *
         * @return int
         */
        public function get($part)
        {
            if (isset(Parts::$parts[$part])) {
                return (int) $this->format(Parts::$parts[$part]);
            }
            throw new \InvalidArgumentException('Undefined date time part ' . $part);
        }

        /**
         * Create new instance Date with `now` time
         *
         * @return \Hope\Util\Date
         */
        public static function now()
        {
            return new Date('now');
        }


    }

}