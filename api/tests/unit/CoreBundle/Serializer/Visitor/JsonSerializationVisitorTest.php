<?php namespace Tests\CoreBundle\Serializer;

use GuzzleHttp\Client;
use Tavro\Bundle\CoreBundle\Entity\User;
use Tests\CoreBundle\TavroCoreTest;
use Tests\SymfonyKernel;

class JsonSerializationVisitorTest extends TavroCoreTest
{

    use SymfonyKernel;

    /**
     * @group Core
     */
    public function testGetResult()
    {

        $data = [
            'foo' => [
                'bar' => 'foobar',
            ]
        ];

        $result = @json_encode((array) $data);

        /**
         * @TODO: we need to actually verify that the root node of the json string
         *      is an array and not an object when returned to the browser.
         */
        $this->assertTrue((json_last_error() === JSON_ERROR_NONE));

    }
}