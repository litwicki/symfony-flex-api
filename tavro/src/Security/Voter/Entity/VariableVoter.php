<?php

namespace Tavro\Security\Voter\Entity;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Tavro\Entity\User;
use Tavro\Entity\OrganizationComment;

use Tavro\Entity\Comment;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Tavro\Security\Voter\TavroVoter;

class VariableVoter extends TavroVoter
{

}