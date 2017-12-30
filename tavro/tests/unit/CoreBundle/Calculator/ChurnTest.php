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

        $actual = ($churnedCount / $totalCount);
        $message = sprintf('Churn rate calculated as CHURNED_COUNT / TOTAL_COUNT; Should be %s', $actual);

        $simple = $calculator::simple($churnedCount, $totalCount);
        $this->assertTrue($simple === $actual, $message);
    }

    public function testAdjusted()
    {
        $calculator = new Churn();

        $churnedCount = 1;
        $startCount = 1;
        $endCount = 2;

        $actual = ($churnedCount / ($startCount + $endCount) / 2);

        $message = sprintf('Adjusted Churn rate calculated as $rate = ($churnedCount / ($startCount + $endCount) / 2)', $actual);

        $adjusted = $calculator::adjusted($churnedCount, $startCount, $endCount);
        $this->assertTrue(($adjusted === $actual), $message);

    }

    public function testPredictive()
    {
        /**
         * @TODO: this test.
         */
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

        $message = sprintf('Average Adjusted calculated as ($churnedCount / ($sum / $n))', $avgAdjusted);

        $this->assertTrue($avgAdjusted === $churn, $message);

    }

}