<?php
/**
 * @package date
 * @copyright Tangent Labs
 * @version $Id: Month.php 1035 2009-04-21 16:00:29Z winterbottomd $
 */

/**
 * Month object
 *
 * Note that the state of the month is stored in the month number and year number
 * properties.
 *
 * @category Taobase
 * @package date
 */
class date_Month
{
    const JANUARY   = 'January';
    const FEBRUARY  = 'February';
    const MARCH     = 'March';
    const APRIL     = 'April';
    const MAY       = 'May';
    const JUNE      = 'June';
    const JULY      = 'July';
    const AUGUST    = 'August';
    const SEPTEMBER = 'September';
    const OCTOBER   = 'October';
    const NOVEMBER  = 'November';
    const DECEMBER  = 'December';

    /**
     * Months in order
     *
     * @var array
     */
    protected static $arrMonths = array(
        1  => self::JANUARY,
        2  => self::FEBRUARY,
        3  => self::MARCH,
        4  => self::APRIL,
        5  => self::MAY,
        6  => self::JUNE,
        7  => self::JULY,
        8  => self::AUGUST,
        9  => self::SEPTEMBER,
        10 => self::OCTOBER,
        11 => self::NOVEMBER,
        12 => self::DECEMBER
    );

    /**
     * Month number
     *
     * @var string
     */
    protected $intMonth;

    /**
     * Year
     *
     * @var int
     */
    protected $intYear;

    // ========
    // CREATION
    // ========

    /**
     * Returns a month object from a given timestamp
     *
     * @param $intTimestamp
     * @return date_Month
     */
    public function createFromTimestamp($intTimestamp=null)
    {
        if (null == $intTimestamp || (int)$intTimestamp <= 0) {
            $intTimestamp = time();
        }
        $objMonth = new self;
        $objMonth->intMonth = date('n', $intTimestamp);
        $objMonth->intYear  = date('Y', $intTimestamp);
        return $objMonth;
    }

    /**
     * Returns a month object from a given timestamp
     *
     * @param $intTimestamp
     * @return date_Month
     */
    public function createFromEnglishFormat($monthAndYear)
    {
        $matches = array();
        preg_match('@(\d+)/(\d{2}|\d{4})$@', $monthAndYear, $matches);
        if (!isset($matches[2])) {
            throw new InvalidArgumentException("Cannot determine month and year from '$monthAndYear'");
        }
        $month = (int)$matches[1];
        $year  = (int)$matches[2];
        if ($year < 100) $year += 2000;
        return new self($month, $year);
    }

    /**
     * Instantiate with month number and year
     *
     * @param $intMonth
     * @param $intYear
     * @return void
     */
    public function __construct($intMonth=null, $intYear=null)
    {
        if (null === $intMonth) {
            $intMonth = date('n');
        } elseif (!array_key_exists($intMonth, self::$arrMonths)) {
            throw new DomainException("Invalid month code '$intMonth' passed to constructor");
        }
        $this->intMonth = $intMonth;
        $this->intYear  = (null === $intYear) ? date('Y') : (int)$intYear;
    }

    // =============
    // INTERROGATION
    // =============

    /**
     * Returns the month number
     *
     * @return int
     */
    public function getMonthNumber()
    {
        return $this->intMonth;
    }

    /**
     * Returns the name of the month
     *
     * @return string
     */
    public function getName()
    {
        return self::$arrMonths[$this->intMonth];
    }

    /**
     * Returns the year
     *
     * @return int
     */
    public function getYear()
    {
        return $this->intYear;
    }

    /**
     * Returns an array representation of this month
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'Number'    => $this->intMonth,
            'Name'      => self::$arrMonths[$this->intMonth],
            'Year'      => $this->intYear,
            'NameShort' => strtolower(substr(self::$arrMonths[$this->intMonth], 0, 3))
        );
    }

    /**
     * Returns a string version of the date
     *
     * @return string
     */
    public function __toString()
    {
        return self::$arrMonths[$this->intMonth].' '.$this->intYear;
    }

    /**
     * Returns a range of months starting with this one.
     *
     * This is commonly used to return information about the next 12 months
     *
     * @param $intNum Number of months to include in the range
     * @return data_MonthRange
     */
    public function getMonthRange($intNumMonths=12)
    {
        $objRange   = new date_MonthRange();
        $objRange[] = $this;
        for ($i=1; $i<=$intNumMonths-1; ++$i) {
            $intMonth = ((($this->intMonth + $i) % 12) != 0) ? (($this->intMonth + $i) % 12) : 12;
            $intYear  = ($intMonth < $this->intMonth) ? $this->intYear + 1 : $this->intYear;
            $objRange[] = new self($intMonth, $intYear);
        }
        return $objRange;
    }

    /**
     * Returns the next month
     *
     * @return date_Month
     */
    public function getNextMonth()
    {
        $intNewMonth = ((($this->intMonth + 1) % 12) != 0) ? (($this->intMonth + 1) % 12) : 12;
        $intNewYear  = ($intNewMonth < $this->intMonth) ? $this->intYear + 1 : $this->intYear;
        return new self($intNewMonth, $intNewYear);
    }

    /**
     * Returns the timestamp object for the first day of the month (at midnight)
     *
     * @return date_Timestamp
     */
    public function getStartDate()
    {
        return new date_Timestamp(mktime(0, 0, 0, $this->intMonth, 1, $this->intYear));
    }

    /**
     * Returns the timestamp object for the last day of the month (at 23:59:59)
     *
     * @return date_Timestamp
     */
    public function getEndDate()
    {
        $objNextMonth = $this->getNextMonth();
        return new date_Timestamp($objNextMonth->getStartDate()->getTimestamp()-1);
    }

    /**
     * Returns a formatted representation of the month
     *
     * @param string $format
     * @return string
     */
    public function getFormatted($format='my')
    {
        $timestamp = mktime(0, 0, 0, $this->getMonthNumber(), 1, $this->getYear());
        return date($format, $timestamp);
    }
}