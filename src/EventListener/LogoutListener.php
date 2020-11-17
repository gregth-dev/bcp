<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutListener
{
    private FlashBagInterface $flashBag;

    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    public function onSymfonyComponentSecurityHttpEventLogoutEvent(LogoutEvent $event): void
    {
        //On récupère l'utilisateur en cours
        /** @var User $user */
        if (($token = $event->getToken()) && $user = $token->getUser()) {
            $userFirstName = ucfirst($user->getFirstName());
        }
        //Si message est présent on l'affiche
        $message = $event->getRequest()->query->get('message');
        if ($message)
            $this->flashBag->add('success', "$userFirstName. $message");
        else
            $this->flashBag->add('success', "A bientôt ! $userFirstName");
    }
}
