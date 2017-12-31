<?php namespace App\Calculator;

/**
 * Class Churn
 *
 * Largely influenced by this blog article:
 *  http://blog.profitwell.com/the-complete-saas-guide-to-calculating-churn-rate-and-keeping-it-simple
 *
 * @package Tavro\Calculator
 */
class Churn
{

    /**
     * Calculate Simple Churn Rate.
     *
     * @param $churnedCount
     * @param $totalCount
     *
     * @return float|int
     * @throws \Exception
     */
    public static function simple($churnedCount, $totalCount)
    {
        try {
            $rate = ($churnedCount / $totalCount);
            return $rate;
        }
        catch(\Exception $e) {
            throw $e;
        }

    }

    /**
     * Here we’re dividing the number of churned customers by an adjusted average of the number
     * of customers throughout the window of "start" and "end" periods.
     *
     * @param $churnedCount
     * @param $startCount
     * @param $endCount
     *
     * @return float
     * @throws \Exception
     */
    public static function adjusted($churnedCount, $startCount, $endCount)
    {
        try {
            $rate = ($churnedCount / ($startCount + $endCount) / 2);
            return $rate;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @see: http://blog.profitwell.com/the-complete-saas-guide-to-calculating-churn-rate-and-keeping-it-simple
     * @TODO: Should we bother with this given the drawbacks?
     */
    public static function predictive()
    {
        try {
            return 0;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $churnedCount
     * @param array $averageCounts
     *
     * @return float
     * @throws \Exception
     */
    public static function averageAdjusted($churnedCount, array $averageCounts)
    {
        try {

            $n = count($averageCounts);
            $sum = 0;

            foreach($averageCounts as $num) {
                $sum += $num;
            }

            $churn = ($churnedCount / ($sum / $n));

            return $churn;

        }
        catch(\Exception $e) {
            throw $e;
        }

    }


}