<?php namespace Tests\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Guzzle\Http\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class CommentTest extends \PHPUnit_Framework_TestCase
{

    public function testComments()
    {
        // create our http client (Guzzle)
        $client = new Client('http://api.tavro.dev/api/v1/', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $request = $client->get('/api/v1/comments', null);
        $response = $request->send();
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