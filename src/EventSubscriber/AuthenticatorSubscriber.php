<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\DeauthenticatedEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AuthenticatorSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $securityLogger;

    private RequestStack $requestStack;

    public function __construct(LoggerInterface $securityLogger, RequestStack $requestStack)
    {
        //$securityLogger pour cibler monolog.security.logger voir symfony console debug:autowiring log
        $this->securityLogger = $securityLogger;
        $this->requestStack = $requestStack;
    }

    /**
     * Tableau des events
     *
     * @return array[string]
     */
    public static function getSubscribedEvents()
    {
        return [
            //Fait référence à la const AuthenticationEvents::AUTHENTICATION_FAILURE
            'security.authentication.failure' => 'onSecurityAuthenticationFailure',
            'security.authentication.success' => 'onSecurityAuthenticationSuccess',
            'security.interactive_login' => 'onSecurityInteractiveLogin',
            'Symfony\Component\Security\Http\Event\LogoutEvent' => 'onSecurityLogout',
            'security.logout_on_change' => 'onSecurityLogoutOnChange',
            'security.switch_user' => 'onSecuritySwitchUser'
        ];
    }

    public function onSecurityAuthenticationFailure(AuthenticationFailureEvent $event): void
    {
        ['user_ip' => $userIp] = $this->getRouteNameAndUserIp();
        /** @var TokenInterface $securityToken */
        $securityToken = $event->getAuthenticationToken();

        ['email' => $emailEntered] = $securityToken->getCredentials();

        $this->securityLogger->info("Un user avec l'adresse ip $userIp a tenté de s'authentifier avec l'adresse email $emailEntered");
    }

    public function onSecurityAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        [
            'user_ip' => $userIp,
            'route_name' => $routeName
        ] = $this->getRouteNameAndUserIp();

        if (!$event->getAuthenticationToken()->getRoleNames()) {
            $this->securityLogger->info("User avec l'adresse ip $userIp connecté à la route $routeName");
        } else {
            /** @var TokenInterface $securityToken */
            $securityToken = $event->getAuthenticationToken();

            $userEmail = $this->getUserEmail($securityToken);

            $this->securityLogger->info("Un utilisateur anonyme ayant l'adresse ip $userIp c'est connecté avec l'email $userEmail");
        }
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        // ...
    }

    public function onSecurityLogout(LogoutEvent $event): void
    {
        // ...
    }

    public function onSecurityLogoutOnChange(DeauthenticatedEvent $event): void
    {
        // ...
    }

    public function onSecuritySwitchUser(SwitchUserEvent $event): void
    {
        // ...
    }

    /**
     * Return userIp and the route_name come from
     *
     * @return array{user_ip: string|null, route_name: mixed}
     */
    private function getRouteNameAndUserIp(): array
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request)
            return [
                'user_ip' => 'No Data',
                'route_name' => 'No Data'
            ];
        return [
            'user_ip' => $request->getClientIp() ?? 'No data',
            'route_name' => $request->attributes->get('_route') ?? 'No data'
        ];
    }

    private function getUserEmail(TokenInterface $securityToken): string
    {
        /** @var User $user */
        $user = $securityToken->getUser();
        return $user->getEmail();
    }
}
