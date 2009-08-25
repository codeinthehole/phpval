<?php
/**
 * @package date
 * @copyright Tangent Labs 2009
 * @version $Id: YearRange.php 1035 2009-04-21 16:00:29Z winterbottomd $
 */

/**
 * Year object
 * 
 * This useful for forming dropdown data for years.  Such as for payment forms.
 * 
 * @package date
 */
class date_YearRange
{
    /**
     * @var date_Year
     */
    private $startYear;
    
    /**
     * @var date_Year
     */
    private $endYear;
    
    /**
     * @param date_Year $startYear
     * @param date_Year $endYear
     */
    public function __construct(date_Year $startYear, date_Year $endYear)
    {
        $this->startYear = $startYear;
        $this->endYear   = $endYear;
    }
    
    /**
     * @return array
     */
    public function toTemplateArray()
    {
        $templateData = array();
        $range = range($this->startYear->getYearNumber(), $this->endYear->getYearNumber());
        foreach ($range as $yearNum) {
            $templateData[] = array(
                'Name' => $yearNum
            );
        }
        return $templateData;
    }
    
    /**
     * @return int
     */
    public function count()
    {
        return $this->endYear->getYearNumber() - $this->startYear->getYearNumber() + 1;
    }
}
