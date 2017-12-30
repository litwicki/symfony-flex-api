<?php namespace Tavro\Calculator;

/**
 * Class Revenue
 *
 * Calculate Monthly Recurring Revenue (MRR), Annual Recurring Revenue (ARR), etc.
 *
 * @package Tavro\Calculator\SaaS
 */
class Revenue
{

    /**
     * Simple MRR by summing all streams.
     * 
     * @param array $amounts
     *
     * @return int|mixed
     * @throws \Exception
     */
    public static function monthly(array $amounts)
    {
        try {

            $sum = 0;
            $mrr = 0;

            foreach($amounts as $amount) {
                $sum += $amount;
            }

            return ($sum / count($amounts));

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * MRR averaged across all revenue sources.
     *
     * @param array $amounts
     *
     * @return float|int
     * @throws \Exception
     */
    public static function monthlyArpu(array $amounts)
    {
        try {

            $sum = 0;
            $mrr = 0;

            foreach($amounts as $amount) {
                $sum += $amount;
            }

            $mrr = ($sum / count($amounts)) * count($amounts);

            return $mrr;

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}