<?php namespace Tests\Unit\ApiBundle\Security\Authentication\Authenticator;

use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\Request;
use Tests\Doctrine;
use Tests\SymfonyKernel;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ObjectManager;

use Tavro\Bundle\CoreBundle\Entity\User;

class JwtAuthenticatorTest extends \PHPUnit_Framework_TestCase
{
    use SymfonyKernel;
    use Doctrine;

    public function createToken($username = 'tavrobot')
    {
        $user = new User();
        $user->setUsername($username);
        return $this->container->get('tavro_api.jwt_token_handler')->createToken($user);
    }

    public function testGetCredentials()
    {

        $before = $this->createToken();

        $request = Request::createFromGlobals();
        $request->headers->set('Authorization', sprintf('Bearer %s', $before));
        $extractor = new AuthorizationHeaderTokenExtractor('Bearer', 'Authorization');
        $after = $extractor->extract($request);

        $this->assertEquals($before, $after);

    }

    public function testGetUser()
    {
        $credentials = $this->createToken();
        $data = $this->container->get('lexik_jwt_authentication.encoder')->decode($credentials);

        $this->assertArrayHasKey('username', $data, 'Decoded token must contain `username` key.');
        $this->assertTrue($data['username'] == 'tavrobot', 'Decoded username must match username passed to token creator.');

    }

}