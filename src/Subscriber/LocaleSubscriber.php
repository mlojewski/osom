<?php

namespace App\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    private $defaultLocale;
    public function __construct(string $defaultLocale = 'en_EN')
    {
        $this->defaultLocale = $defaultLocale;
    }
    
    public function onKernelRequest (GetResponseEvent $event)
    {
        $request = $event->getRequest();
        
        if (!$request->hasPreviousSession()) {
            return;
        }
        if($request->get('lang') !== null) {
            $request->setLocale($request->getSession()->get('_locale', $request->get('lang')));
        } elseif ($locale = $request->attributes->get('locale')) {
            $request->getSession()->set('_locale', $locale);
        } else {
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }
    }
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['onKernelRequest', 20]
            ]
        ];
    }
}