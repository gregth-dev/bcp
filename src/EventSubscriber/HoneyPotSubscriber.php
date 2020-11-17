<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class qui vient tester la présence de bot au moment de la soumission du formulaire d'inscription
 */
class HoneyPotSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $honeyPotLogger;

    private RequestStack $requestStack;

    public function __construct(LoggerInterface $honeyPotLogger, RequestStack $requestStack)
    {
        $this->honeyPotLogger = $honeyPotLogger;
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => 'checkHoneyJar'
        ];
    }

    /**
     * Envoi une erreur en cas de modification du formulaire ou de tentative par un bot
     *
     * @param FormEvent $event
     * @return void
     */
    public function checkHoneyJar(FormEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request)
            return;

        $data = $event->getData();

        if (!array_key_exists('phone', $data) || !array_key_exists('city', $data))
            throw new HttpException(400, "Don't touch my form !!");

        [
            'phone' => $phone,
            'city' => $city
        ] = $data;

        if ($phone !== '' || $city !== '') {
            $this->honeyPotLogger->info("Bot detected with ip {$request->getClientIp()}");
            throw new HttpException(403, "Bot detected !!");
        }
    }
}
