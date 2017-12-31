<?php namespace App\Calculator;

/**
 * Class LifetimeValue
 *
 *
 *
 * @package Tavro\Calculator
 */
class LifetimeValue
{

    public static function historicLifetimeValue(array $transactions, $avgGrossMargin)
    {

        try {

            $totalValue = 0;

            foreach($transactions as $transaction) {
                $totalValue += $transaction;
            }

            return ($totalValue / $avgGrossMargin);

        }
        catch(\Exception $e) {
            throw $e;
        }

    }

    /**
     * Calculate the Predictive LTV of a customer.
     *
     * This all equates to Gross Margin Contribution per Customer (GML)
     *  which we use to build the detailed predictive LTV
     *
     * @param float $avgPeriodTransactionsNum
     * @param float $avgOrderValue
     * @param float $avgLifespan
     * @param float $avgGrossMargin
     *
     * @return float
     * @throws \Exception
     */
    public static function predictiveLifetimeValue(float $avgPeriodTransactionsNum, float $avgOrderValue, float $avgLifespan, float $avgGrossMargin)
    {
        try {

            $clv = ( ($avgPeriodTransactionsNum * $avgOrderValue) * $avgGrossMargin) * $avgLifespan;

            return $clv;


        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Calculate the Detailed LTV of a Customer while factoring retention rates and discount rates.
     *
     * @param $avgPeriodTransactionsNum float number of transactions in a given time period
     * @param $avgOrderValue            float order value
     * @param $avgLifespan              float customer lifespan (same time period as avgPeriodTransactionNum)
     * @param $avgGrossMargin           float gross margin
     *
     * This all equates to Gross Margin Contribution per Customer (GML)
     *   which we use to build the detailed predictive LTV
     *
     * @return mixed
     * @throws \Exception
     */
    public static function predictiveComplex(float $avgPeriodTransactionsNum, float $avgOrderValue, float $avgLifespan, float $avgGrossMargin, float $retentionRate, float $discountRate)
    {
        try {

            $gml = self::predictiveLifetimeValue($avgPeriodTransactionsNum, $avgOrderValue, $avgLifespan, $avgGrossMargin);
            return ($gml * ($retentionRate / (1 + $discountRate - $retentionRate)));

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}