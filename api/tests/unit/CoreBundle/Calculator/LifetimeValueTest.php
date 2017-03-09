<?php namespace Tests\Unit\CoreBundle\Calculator;

use GuzzleHttp\Client;
use Tavro\Bundle\CoreBundle\Calculator\LifetimeValue;
use Tavro\Bundle\CoreBundle\Entity\User;

use Tests\SymfonyKernel;

class LifetimeValueTest extends \PHPUnit_Framework_TestCase
{
    use SymfonyKernel;
    
    public function testHistoricLifetimeValue()
    {

        $transactions = [100,100,200,100];
        $avgGrossMargin = 10;

        $totalValue = 0;

        foreach($transactions as $transaction) {
            $totalValue += $transaction;
        }

        $ltv = ($totalValue / $avgGrossMargin);

        $calculator = new LifetimeValue();

        $this->assertTrue(($ltv == $calculator::historicLifetimeValue($transactions, $avgGrossMargin)), 'History Lifetime Value calculated as ($totalValue / $avgGrossMargin)');

    }

    public function testPredictiveLifetimeValue()
    {
        $avgPeriodTransactionsNum = 10;
        $avgOrderValue = 100;
        $avgLifespan = 360;
        $avgGrossMargin = 50;

        $clv = ( ($avgPeriodTransactionsNum * $avgOrderValue) * $avgGrossMargin) * $avgLifespan;

        $calculator = new LifetimeValue();
        $value = $calculator::predictiveLifetimeValue($avgPeriodTransactionsNum, $avgOrderValue, $avgLifespan, $avgGrossMargin);

        $this->assertTrue(($clv == $value), sprintf('%s is not equal to %s', $clv, $value));
    }

    public function testPredictiveComplex()
    {

        $avgPeriodTransactionsNum = 10;
        $avgOrderValue = 125;
        $avgLifespan = 360;
        $avgGrossMargin = 50;
        $retentionRate = 90;
        $discountRate = 10;

        $calculator = new LifetimeValue();
        $gml = $calculator::predictiveLifetimeValue($avgPeriodTransactionsNum, $avgOrderValue, $avgLifespan, $avgGrossMargin);
        $value = ($gml * ($retentionRate / (1 + $discountRate - $retentionRate)));

        $this->assertTrue(($value == $calculator::predictiveComplex($avgPeriodTransactionsNum, $avgOrderValue, $avgLifespan, $avgGrossMargin, $retentionRate, $discountRate)), 'Predictive Complex LTV is ($gml * ($retentionRate / (1 + $discountRate - $retentionRate)))');

    }

}