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
        public function __construct(Date $start, Date $end, Interval $interval = null)
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