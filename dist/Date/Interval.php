<?php

namespace Hope\Util\Date
{

    use DateInterval;

    /**
     * Class Interval
     *
     * @package Hope\Util\Date
     */
    class Interval extends DateInterval
    {

        /**
         * @param string $interval
         */
        public function __construct($interval = null)
        {
            if ($interval) {
                parent::__construct($interval);
            }
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
         * @return Interval
         */
        public function addYear($y)
        {
            $this->y += (int) $y;

            return $this;
        }

        /**
         * Add month to interval
         *
         * @param int $m
         *
         * @return Interval
         */
        public function addMonth($m)
        {
            $this->m += (int) $m;

            return $this;
        }

        /**
         * Add month to interval
         *
         * @param int $w
         *
         * @return Interval
         */
        public function addWeek($w)
        {
            $this->d += (int) $w * 7;

            return $this;
        }

        /**
         * Add days to interval
         *
         * @param int $d
         *
         * @return \Hope\Util\Date\Interval
         */
        public function addDay($d = 1){
            $this->d += (int) $d;

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
            $this->h += (int) $h;

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
            $this->i += (int) $m;

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
            $this->s += (int) $s;

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
            $interval = new static();
            $interval->invert = $di->invert;
            $interval->days = $di->days;
            $interval->y = $di->y;
            $interval->m = $di->m;
            $interval->d = $di->d;
            $interval->h = $di->h;
            $interval->i = $di->i;
            $interval->s = $di->s;

            return $interval;
        }

    }

}