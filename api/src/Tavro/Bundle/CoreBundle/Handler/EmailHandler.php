<?php namespace Tavro\Bundle\CoreBundle\Handler;

use Tavro\Bundle\CoreBundle\Services\TavroMailer;
use Tavro\Bundle\CoreBundle\Entity\User;

class EmailHandler
{
    protected $mailer;

    public function __construct(TavroMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendPasswordReset(User $user)
    {

        $params = [
            'subject'    => 'Reset your password',
            'recipients' => [$user->getEmail()],
            'type'       => 'password-reset',
            'user'       => $user,
        ];

        $this->mailer->sendEmail($params);

    }
}