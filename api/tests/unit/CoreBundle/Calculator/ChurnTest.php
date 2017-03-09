<?php namespace Tests\Unit\CoreBundle\Calculator;

use GuzzleHttp\Client;
use Tavro\Bundle\CoreBundle\Calculator\Churn;
use Tavro\Bundle\CoreBundle\Entity\User;

use Tests\SymfonyKernel;

class ChurnTest extends \PHPUnit_Framework_TestCase
{
    use SymfonyKernel;

    public function testSimple()
    {
        $calculator = new Churn();

        $churnedCount = 1;
        $totalCount = 10;

        $simple = $calculator::simple($churnedCount, $totalCount);
        $this->assertTrue($simple === ($churnedCount / $totalCount), 'Churn rate calculated as CHURNED_COUNT / TOTAL_COUNT; 1/2 should be .5');
    }

    public function testAdjusted()
    {
        $calculator = new Churn();

        $churnedCount = 1;
        $startCount = 1;
        $endCount = 2;

        $adjusted = $calculator::adjusted($churnedCount, $startCount, $endCount);
        $this->assertTrue(($adjusted === ($churnedCount / ($startCount + $endCount) / 2)), 'Adjusted Churn rate calculated as $rate = ($churnedCount / ($startCount + $endCount) / 2)');

    }

    public function testPredictive()
    {
        $this->assertTrue(1 === 1, 'This test is placeholder for the real method when its implemented');
    }

    public function testAverageAdjusted()
    {

        $averageCounts = [1, 2, 3];
        $churnedCount = 1;

        $n = count($averageCounts);
        $sum = 0;

        foreach($averageCounts as $num) {
            $sum += $num;
        }

        $avgAdjusted = ($churnedCount / ($sum / $n));

        $calculator = new Churn();
        $churn = $calculator::averageAdjusted($churnedCount, $averageCounts);

        $this->assertTrue($avgAdjusted === $churn, 'Average Adjusted calculated as ($churnedCount / ($sum / $n))');

    }

}