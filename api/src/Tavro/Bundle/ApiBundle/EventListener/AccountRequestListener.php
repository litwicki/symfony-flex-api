<?php namespace Tavro\Bundle\ApiBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Tavro\Bundle\CoreBundle\Exception\Entity\Account\AccountStatusDisabledException;
use Tavro\Bundle\CoreBundle\Exception\Entity\Account\AccountStatusInvalidException;
use Tavro\Bundle\CoreBundle\Exception\Entity\Account\AccountStatusOtherException;
use Tavro\Bundle\CoreBundle\Exception\Entity\Account\AccountStatusPendingException;
use Tavro\Bundle\CoreBundle\Model\EntityInterface\AccountEntityInterface;
use Tavro\Bundle\CoreBundle\Entity\Account;

class AccountRequestListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            // don't do anything if it's not the master request
            return;
        }

        /**
         * Validate that every Api Request related to an Account Entity
         * is only done on an Account that is in STATUS_ENABLED.
         */

        $request = $event->getRequest();
        $_route  = $request->attributes->get('_route');

        if(preg_match($_route, '//')) {

            $params = $request->attributes->get('_route_params');
            $accountId = $params['account'];

            try {

                //@TODO: get the entity manager:
                $em = '';

                $account = $em->getRepository('TavroCoreBundle:Account')->find($accountId);

                if($account instanceof Account && $account->getStatus() != $account::STATUS_ENABLED) {

                    switch($account->getStatus()) {

                        case $account::STATUS_DISABLED:
                            throw new AccountStatusDisabledException(sprintf('Account %s is not accessible because it has been disabled.', $account->__toString()));
                            break;

                        case $account::STATUS_PENDING:
                            throw new AccountStatusPendingException(sprintf('Account %s is not accessible because it is pending.', $account->__toString()));
                            break;

                        case $account::STATUS_OTHER:
                            throw new AccountStatusOtherException(sprintf('Account %s is not accessible.', $account->__toString()));
                            break;

                        default:
                            throw new AccountStatusInvalidException(sprintf('Account %s could not be loaded with status %s', $account->__toString(), $account->getStatus()));
                            break;

                    }

                }

            }
            catch(\Exception $e) {
                throw $e;
            }

        }

    }
}