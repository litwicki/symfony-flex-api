<?php namespace Tavro\Handler;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Tavro\Entity\User;
use Tavro\Exception\Api\ApiAccessDeniedException;

use Tavro\Event\User\UserActivatedEvent;

class SecurityHandler implements ContainerAwareInterface
{
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Activate a new User.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $data
     *
     * @return mixed
     * @throws \Exception
     */
    public function activate(Request $request, array $data = array())
    {
        try {

            if(false === (isset($data['guid']))) {
                throw new ApiAccessDeniedException('Invalid or missing `guid`');
            }

            $guid = $data['guid'];

            $userHandler = $this->container->get('tavro.handler.users');

            $user = $userHandler->repository->findOneBy([
                'guid' => $guid,
                'status' => User::STATUS_PENDING
            ]);

            if(false === ($user instanceof User)) {
                throw new ApiAccessDeniedException('No User is pending activation with that `guid`');
            }

            $em = $this->container->get('doctrine')->getEntityManager();

            /**
             * We cannot PATCH: here because we're not authenticating to complete this request.
             */
            $user->setStatus($user::STATUS_ENABLED);
            $user->setActivationDate($this->container->get('tavro.date_handler')->now());
            $em->persist($user);
            $em->flush();

            $event = new UserActivatedEvent($user);
            $this->container->get('event_dispatcher')->dispatch(UserActivatedEvent::NAME, $event);

            return [
                'message' => sprintf('User %s has been activated.', $user->__toString())
            ];

        }
        catch(\Exception $e) {
            throw $e;
        }
    }
}