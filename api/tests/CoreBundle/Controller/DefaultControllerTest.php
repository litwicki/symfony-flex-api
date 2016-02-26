<?php namespace Tests\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Guzzle\Http\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class CommentTest extends \PHPUnit_Framework_TestCase
{
    public function testRoles()
    {
        // create our http client (Guzzle)
        $client = new Client('http://api.tavro.dev', array(
            'request.options' => array(
                'exceptions' => false,
                'headers' => array(
                    'X-AUTH-TOKEN' => 'tavrobot-api-key'
                )
            )
        ));

        $request = $client->get('/api/v1/roles', null);
        $response = $request->send();

        $data = $response->json();

        $roles = array(
            'ROLE_USER',
            'ROLE_API',
            'ROLE_DEVELOPER',
            'ROLE_ADMIN',
            'ROLE_SUPERUSER'
        );

        $this->assertNotEmpty($data);

        foreach($data as $role) {
            $this->assertTrue(in_array($role['role'], $roles));
        }

    }

    public function testEntities()
    {
        // create our http client (Guzzle)
        $client = new Client('http://api.tavro.dev', array(
            'request.options' => array(
                'exceptions' => false,
                'headers' => array(
                    'X-AUTH-TOKEN' => 'tavrobot-api-key'
                )
            )
        ));

        $entities = array(
            'comments',
            'customers',
            'customer_comments',
            'expenses',
            'expense_categories',
            'expense_comments',
            'expense_tags',
            'funding_rounds',
            'funding_round_comments',
            'funding_round_shareholders',
            'images',
            'tavro_nodes',
            'node_comments',
            'nodes_read',
            'organizations',
            'products',
            'product_categories',
            'product_images',
            'revenues',
            'revenue_categories',
            'revenue_comments',
            'revenue_products',
            'revenue_services',
            'revenue_tags',
            'services',
            'service_categories',
            'service_images',
            'shareholders',
            'tags',
            'users',
            'user_organizations',
            'user_roles'
        );

        foreach($entities as $entity) {

            $url = sprintf('/api/v1/%s', $entity);
            $request = $client->get($url, null);
            $response = $request->send();

            $data = $response->json();

            $this->assertNotEmpty($data);

        }

    }

//    public function testCommentPost()
//    {
//        // create our http client (Guzzle)
//        $client = new Client('http://localhost:8000', array(
//            'request.options' => array(
//                'exceptions' => false,
//            )
//        ));
//
//        $nickname = 'ObjectOrienter'.rand(0, 999);
//        $data = array(
//            'nickname' => $nickname,
//            'avatarNumber' => 5,
//            'tagLine' => 'a test dev!'
//        );
//
//        $request = $client->post('/api/programmers', null, json_encode($data));
//        $response = $request->send();
//    }

}