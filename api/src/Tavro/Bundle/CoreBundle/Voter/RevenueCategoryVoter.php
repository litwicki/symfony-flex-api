<?php

namespace Tavro\Bundle\CoreBundle\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Tavro\Bundle\CoreBundle\Entity\RevenueCategory;
use Tavro\Bundle\CoreBundle\Entity\User;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RevenueCategoryVoter
 *
 * @package Tavro\Bundle\CoreBundle\Voter
 */
class RevenueCategoryVoter implements VoterInterface, ContainerAwareInterface
{

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Allows full access to members belonging to the growth cse, view access to outside admins.
     *
     * @param User $user
     * @param \Tavro\Bundle\CoreBundle\Entity\RevenueCategory $entity
     * @param string  $attribute
     *
     * @throws \Exception
     * @return int
     */
    public function checkAccess($user, RevenueCategory $entity, $attribute)
    {

        if($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return VoterInterface::ACCESS_GRANTED;
        }

        if($attribute == self::PATCH) {
            return VoterInterface::ACCESS_GRANTED;
        }

        // Allow all creates
        if($attribute == self::CREATE) {
            return VoterInterface::ACCESS_GRANTED;
        }

        // Allow all views
        if($attribute == self::VIEW) {
            return VoterInterface::ACCESS_GRANTED;
        }

        $modifyDate = $entity->getCreateDate();
        $modifyDate->modify("+30 minutes");

        $now = new \DateTime();

        /**
         * Only Admins, or the author of the RevenueCategory can edit
         */
        if($attribute == self::EDIT || $attribute == self::PATCH) {

            if($user->getId() === $entity->getUser()->getId()) {

                /**
                 * Only allow the "author" to edit their RevenueCategory within 30 minutes of RevenueCategorying
                 */
                if($now < $modifyDate) {
                    return VoterInterface::ACCESS_GRANTED;
                }
                return VoterInterface::ACCESS_GRANTED;

            }

        }

        /**
         *  Only ROLE_ADMIN or the owner can delete
         */
        if($attribute == self::DELETE || $attribute == self::REMOVE) {

            if($user->getId() === $entity->getUser()->getId()) {

                /**
                 * Only allow the "author" to edit their RevenueCategory within 30 minutes of RevenueCategorying
                 */
                if($now < $modifyDate) {
                    return VoterInterface::ACCESS_GRANTED;
                }

            }

        }

        // Deny all other requests
        return VoterInterface::ACCESS_DENIED;
    }

    const CREATE = 'create';
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';
    const PATCH = 'patch';
    const REMOVE = 'remove';

    /**
     * Returns true if the attribute matches known attributes.
     *
     * @param string $attribute
     *
     * @return bool
     */
    public function supportsAttribute($attribute) {
        return in_array($attribute, array(self::PATCH, self::REMOVE, self::CREATE, self::VIEW, self::EDIT, self::DELETE));
    }

    /**
     * Returns true if object is an instance of GrowthCase.
     *
     * @param object $class
     *
     * @return bool
     */
    public function supportsClass($class) {
        return $class instanceof RevenueCategory;
    }

    /**
     * Returns if the user should have access to the entity.
     *
     * @param TokenInterface $token
     * @param object $entity
     * @param array $attributes
     *
     * @return int
     */
    public function vote(TokenInterface $token, $entity, array $attributes) {
        //throw new \Symfony\Component\Security\Acl\Exception\Exception('ERORR');
        //return VoterInterface::ACCESS_GRANTED;
        if (!$this->supportsClass($entity)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        if (1 !== count($attributes)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        $attribute = $attributes[0];

        if(!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        $user = $token->getUser();

        return $this->checkAccess($user, $entity, $attribute);

    }

}
