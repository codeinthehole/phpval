<?php
/**
 * @package date
 * @version $Id: Year.php 1035 2009-04-21 16:00:29Z winterbottomd $
 */

/**
 * Simple year object
 * 
 * @package date
 */
class date_Year
{
    /**
     * @var string
     */
    private $yearNumber;
    
    /**
     * @param string $yearString
     */
    public function __construct($yearNumber=null)
    {
        $this->yearNumber = (is_null($yearNumber)) ? (int)date('Y') : (int)$yearNumber;
    }
    
    /**
     * @return int
     */
    public function getYearNumber()
    {
        return $this->yearNumber;
    }
    
    /**
     * @param int $offset
     * @return date_YearRange
     */
    public function getRange($offset)
    {
        if ($offset < 0) {
            return new date_YearRange(new date_Year($this->yearNumber+$offset), $this);
        } 
        return new date_YearRange($this, new date_Year($this->yearNumber+$offset));
    }
}
