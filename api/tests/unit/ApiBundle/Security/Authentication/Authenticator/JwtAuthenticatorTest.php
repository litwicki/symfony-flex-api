<?php namespace Tests\ApiBundle\Security\Authentication\Authenticator;

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
        return $this->container->get('lexik_jwt_authentication.encoder')->encode(['username' => $username]);
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

    }

    public function testGetExpiredUser()
    {
        $credentials = $this->createToken('expired');
    }

    public function testInvalidUser()
    {
        $credentials = $this->createToken('invalid');
    }

    public function testUnverifiedUser()
    {
        $credentials = $this->createToken('unverified');
    }

}