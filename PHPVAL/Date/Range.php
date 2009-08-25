<?php
/**
 * @package date
 * @copyright Tangent Labs
 * @version $Id: Range.php 1035 2009-04-21 16:00:29Z winterbottomd $
 */

/**
 * Date range
 *
 * @category Taobase
 * @package date
 */
class date_Range
{
    /**
     * @var date_Timestamp
     */
    protected $startDate;
    
    /**
     * @var date_Timestamp
     */
    protected $endDate;
    
    /**
     * @param date_Timestamp $StartDate
     * @param date_Timestamp $EndDate
     */
    public function __construct(date_Timestamp $StartDate, date_Timestamp $EndDate)
    {
        $this->startDate = $StartDate;
        $this->endDate   = $EndDate;    
    }
    
    /**
     * @return date_Timestamp
     */
    public function getStartDate()
    {
        return $this->startDate;
    }
    
    /**
     * @return date_Timestamp
     */
    public function getEndDate()
    {
        return $this->endDate;
    }
    
    /**
     * Checks whether a date is contained within this range
     * 
     * @param date_Timestamp $date
     * @return boolean
     */
    public function contains(date_Timestamp $date)
    {
        return ($this->startDate->isBefore($date) && $this->endDate->isAfter($date));
    }
}