<?php namespace Tests\CoreBundle\Calculator;

use GuzzleHttp\Client;
use Tavro\Bundle\CoreBundle\Calculator\LifetimeValue;
use Tavro\Bundle\CoreBundle\Calculator\Revenue;
use Tavro\Bundle\CoreBundle\Entity\User;
use Tests\CoreBundle\TavroCoreTest;
use Tests\SymfonyKernel;

class RevenueTest extends TavroCoreTest
{
    use SymfonyKernel;

    public function testMonthly()
    {

        $amounts = [100,100,100,100,200,200,500];
        $sum = 0;
        $mrr = 0;

        foreach($amounts as $amount) {
            $sum += $amount;
        }

        $mrr = ($sum / count($amounts));

        $calculator = new Revenue();

        $this->assertTrue(($mrr == $calculator::monthly($amounts)), 'Monthly calculated as ($sum / count($amounts))');

    }

    public function testMonthlyArpu()
    {
        $amounts = [100,100,100,100,200,200,500];
        $sum = 0;
        $mrr = 0;

        foreach($amounts as $amount) {
            $sum += $amount;
        }

        $mrr = ($sum / count($amounts)) * count($amounts);

        $calculator = new Revenue();
        $value = $calculator::monthlyArpu($amounts);

        $this->assertTrue(($mrr == $value), sprintf('%s is not equal to %s', $mrr, $value));

    }

}