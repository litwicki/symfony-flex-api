<?php namespace Tests\ApiBundle\Security\Authentication\Authenticator;

use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\Request;
use Tests\Doctrine;
use Tests\SymfonyKernel;

class JwtAuthenticatorTest extends \PHPUnit_Framework_TestCase
{
    use SymfonyKernel;
    use Doctrine;

    public function testGetCredentials()
    {

        $before = $this->container->get('lexik_jwt_authentication.encoder')->encode(['username' => 'tavrobot']);

        $request = Request::createFromGlobals();
        $request->headers->set('Authorization', sprintf('Bearer %s', $before));
        $extractor = new AuthorizationHeaderTokenExtractor('Bearer', 'Authorization');
        $after = $extractor->extract($request);

        $this->assertEquals($before, $after);

    }

}