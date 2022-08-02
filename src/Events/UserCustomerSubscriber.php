<?php

namespace App\Events;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserCustomerSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;
    /**
     *
     * @var ValidatorInterface 
     */
    private $validatorInterface;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setCustomerForUser', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function setCustomerForUser(ViewEvent $event)
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if ($user instanceof User && $method === "POST") {
            //choper l'utilisateur actuelement connecté
            $customer = $this->security->getUser();
            //assigner le client à l'utilisateur qu'on est entrain de créer
            $user->setCustomer($customer);
        }
    }
}
