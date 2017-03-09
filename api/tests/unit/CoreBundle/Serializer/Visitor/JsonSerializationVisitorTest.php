<?php namespace Tests\Unit\CoreBundle\Serializer;

use GuzzleHttp\Client;
use Tavro\Bundle\CoreBundle\Entity\User;

use Tests\SymfonyKernel;

class JsonSerializationVisitorTest extends \PHPUnit_Framework_TestCase
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