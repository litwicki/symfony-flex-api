<?php

namespace Tavro\Bundle\CoreBundle\Security\Voter\Entity;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\OrganizationComment;
use Tavro\Bundle\CoreBundle\Entity\Customer;
use Tavro\Bundle\CoreBundle\Entity\Comment;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Tavro\Bundle\CoreBundle\Security\Voter\TavroVoter;

class FundingRoundShareholderVoter extends TavroVoter
{

}