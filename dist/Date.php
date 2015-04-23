<?php

namespace Hope\Util
{

    use DateTime;
    use DateTimeZone;
    use Hope\Util\Date\Interval;
    use Hope\Util\Date\Period;

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
        const JANUARY   = 0;
        const FEBRUARY  = 1;
        const MARCH     = 2;
        const APRIL     = 3;
        const MAY       = 4;
        const JUNE      = 5;
        const JULE      = 6;
        const AUGUST    = 7;
        const SEPTEMBER = 8;
        const OCTOBER   = 9;
        const NOVEMBER  = 10;
        const DECEMBER  = 11;

        /**
         * The day constants
         */
        const SUNDAY    = 0;
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
         * Subtract days
         *
         * @param int $d
         *
         * @return Date
         */
        public function subDays($d = 1)
        {
            return $this->setTimestamp($this->getTimestamp() - ($d * Date::DAY));
        }

        /**
         * Subtract hours
         *
         * @param int $h
         *
         * @return Date
         */
        public function subHours($h = 1)
        {
            return $this->setTimestamp($this->getTimestamp() - ($h * Date::HOUR));
        }

        /**
         * Subtract minutes
         *
         * @param int $m
         *
         * @return Date
         */
        public function subMinutes($m = 1)
        {
            return $this->setTimestamp($this->getTimestamp() - ($m * Date::MINUTE));
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