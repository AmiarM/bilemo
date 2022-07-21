<?php

namespace App\Events;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedSubscriber
{
    public function updateJWTData(JWTCreatedEvent $event)
    {
        //recupérer le user(firstName and lastName)
        /**
         * @var User
         */
        $user = $event->getUser();
        //enrichir les datas avec ces données 
        $data = $event->getData();
        $data['firstName'] = $user->getLastName();
        $data['lastName'] = $user->getLastName();
        $event->setData($data);
    }
}
