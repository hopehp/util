<?php

namespace Hope\Util\Date
{

    use Hope\Util\Date;

    /**
     * Class Period
     *
     * @package Hope\Util\Date
     */
    class Period
    {

        /**
         * Period step interval
         *
         * By default interval equals 1 day
         *
         * @var \Hope\Util\Date\Interval
         */
        protected $interval;

        /**
         * Iterate datetime object
         *
         * @var \Hope\Util\Date
         */
        protected $datetime;

        /**
         * Period start date
         *
         * @var \Hope\Util\Date
         */
        protected $start;

        /**
         * Period end date
         *
         * @var \Hope\Util\Date
         */
        protected $end;

        /**
         * @param \Hope\Util\Date          $start
         * @param \Hope\Util\Date          $end
         * @param \Hope\Util\Date\Interval $interval
         */
        function __construct(Date $start, Date $end, Interval $interval = null)
        {
            if ($interval) {
                $this->setInterval($interval);
            }

            $this->setStart($start)->setEnd($end);
        }

        /**
         * Set iteration interval
         *
         * @param \Hope\Util\Date\Interval $interval
         *
         * @return \Hope\Util\Date\Period
         */
        public function setInterval(Interval $interval)
        {
            $this->interval = $interval;
            return $this;
        }

        /**
         * Returns iteration interval
         *
         * @return \Hope\Util\Date\Interval
         */
        public function getInterval()
        {
            if (null === $this->interval) {
                $this->interval = Interval::make()->addDay(1);
            }
            return $this->interval;
        }

        /**
         * @param \Hope\Util\Date $start
         *
         * @return \Hope\Util\Date\Period
         */
        public function setStart(Date $start)
        {
            $this->start = $start;
            return $this;
        }

        /**
         * Returns period start date
         *
         * @return \Hope\Util\Date
         */
        public function getStart()
        {
            return $this->start;
        }

        /**
         * Set period end date
         *
         * @param \Hope\Util\Date $end
         *
         * @return \Hope\Util\Date\Period
         */
        public function setEnd(Date $end)
        {
            $this->end = $end;
            return $this;
        }

        /**
         * Returns period end date
         *
         * @return \Hope\Util\Date
         */
        public function getEnd()
        {
            return $this->end;
        }

        /**
         * Iterate dates
         *
         * @return \Generator
         */
        public function each()
        {
            if ($this->datetime === null) {
                $this->datetime = $this->getStart()->copy();
            }
            yield $this->datetime->add($this->getInterval());
        }
        
        /**
         * Alias for Period::setStart
         *
         * @param \Hope\Util\Date $date
         *
         * @return \Hope\Util\Date\Period
         */
        public function from(Date $date)
        {
            return $this->setStart($date);
        }

        /**
         * Alias for Period::setEnd
         *
         * @param \Hope\Util\Date $date
         *
         * @return \Hope\Util\Date\Period
         */
        public function to(Date $date)
        {
            return $this->setEnd($date);
        }

    }

}