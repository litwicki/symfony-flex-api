<?php

namespace App\Security\Voter\Entity;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Entity\User;
use App\Entity\OrganizationComment;

use App\Entity\Comment;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use App\Security\Voter\TavroVoter;

class RevenueCommentVoter extends TavroVoter
{

}