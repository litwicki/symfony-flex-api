<?php namespace Tests\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Guzzle\Http\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class ApiControllerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */

    protected function createAuthenticatedClient($username = 'tavrobot', $password = 'Password1!')
    {
        $client = new Client();
        $response = $client->post('/api/login_check', array(
            '_username' => $username,
            '_password' => $password,
        ));

        $data = json_decode($response, true);

        $client = new Client('http://api.tavro.dev', array(
            'request.options' => array(
                'exceptions' => false,
                'headers' => array(
                    'Bearer' => $data['token']
                )
            )
        ));

        return $client;
    }

//    public function testRoles()
//    {
//
//        $client = $this->createAuthenticatedClient();
//
//        $request = $client->get('/api/v1/roles', null);
//        $response = $request->send();
//
//        $data = $response->json();
//
//        $roles = array(
//            'ROLE_USER',
//            'ROLE_API',
//            'ROLE_DEVELOPER',
//            'ROLE_ADMIN',
//            'ROLE_SUPERUSER'
//        );
//
//        $this->assertNotEmpty($data);
//
//        foreach($data as $role) {
//            $this->assertTrue(in_array($role['role'], $roles));
//        }
//
//    }
//
//    public function testEntities()
//    {
//        // create our http client (Guzzle)
//        $client = new Client('http://api.tavro.dev', array(
//            'request.options' => array(
//                'exceptions' => false,
//                'headers' => array(
//                    'X-AUTH-TOKEN' => 'tavrobot-api-key'
//                )
//            )
//        ));
//
//        $entities = array(
//            'comments',
//            'customers',
//            'customer_comments',
//            'expenses',
//            'expense_categories',
//            'expense_comments',
//            'expense_tags',
//            'funding_rounds',
//            'funding_round_comments',
//            'funding_round_shareholders',
//            'images',
//            'tavro_nodes',
//            'node_comments',
//            'nodes_read',
//            'organizations',
//            'products',
//            'product_categories',
//            'product_images',
//            'revenues',
//            'revenue_categories',
//            'revenue_comments',
//            'revenue_products',
//            'revenue_services',
//            'revenue_tags',
//            'services',
//            'service_categories',
//            'service_images',
//            'shareholders',
//            'tags',
//            'users',
//            'user_organizations',
//            'user_roles'
//        );
//
//        foreach($entities as $entity) {
//
//            $url = sprintf('/api/v1/%s', $entity);
//            $request = $client->get($url, null);
//            $response = $request->send();
//
//            $data = $response->json();
//
//            $this->assertNotEmpty($data);
//
//        }
//
//    }

}