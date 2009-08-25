<?php
/**
 * @package date
 * @copyright Tangent Labs
 * @version $Id: MonthRange.php 1035 2009-04-21 16:00:29Z winterbottomd $
 */

/**
 * Month range object
 *
 * @author David Winterbottom
 * @package date
 */
class date_MonthRange extends ArrayObject
{
    /**
     * Returns a multiarray representation of this month range
     *
     * @return array
     */
    public function toMultiArray()
    {
        $arrayData = array();
        foreach ($this as $month) {
            if (method_exists($month, 'toArray')) {
                $arrayData[] = $month->toArray();
            }
        }
        return $arrayData;
    }

    /**
     * Adds a new month to the range
     *
     * @param $Month
     * @return date_MonthRange
     */
    public function addMonth(date_Month $Month)
    {
        $this[] = $Month;
        return $this;
    }
}