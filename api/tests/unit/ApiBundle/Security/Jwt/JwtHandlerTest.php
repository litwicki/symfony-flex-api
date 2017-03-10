<?php namespace Tests\Unit\ApiBundle\Security\Jwt;

use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\Request;
use Tests\Doctrine;
use Tests\SymfonyKernel;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ObjectManager;

use Tavro\Bundle\CoreBundle\Entity\User;

class JwtHandlerTest extends \PHPUnit_Framework_TestCase
{
    use SymfonyKernel;
    use Doctrine;

    public function testCreateToken($username = 'tavrobot')
    {
        $user = new User();
        $user->setUsername($username);
        $token = $this->container->get('tavro_api.jwt_token_handler')->createToken($user);
        $data = $this->container->get('lexik_jwt_authentication.encoder')->decode($token);

        $this->assertArrayHasKey('username', $data, 'Decoded Token should contain `username` key.');
    }

}