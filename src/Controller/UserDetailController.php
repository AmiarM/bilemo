<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

class UserDetailController extends AbstractController
{
    /**
     *
     * @var UserRepository
     */
    protected $userRepository;
    /** 
     * @var SerializerInterface
     */
    protected $serializerInterface;

    public function __construct(UserRepository $userRepository, SerializerInterface $serializerInterface)
    {
        $this->userRepository  = $userRepository;
        $this->serializerInterface = $serializerInterface;
    }
    /**
     * @Route("/user/detail", name="app_user_detail")
     */
    /**
     * @Route(
     *     name="route_customers_user",
     *     path="/api/users/{id}",
     *     defaults={"_api_resource_class"=User::class,
     *               "_api_item_operation_name"="read:Users:collection"
     *     }
     * )
     * @Method("GET")
     */
    public function findUserByCustomer($id)
    {
        $customerConnecte = $this->getUser();
        if ($customerConnecte) {
            $user = $this->userRepository->findBy([
                'id' => $id
            ]);
            $json = $this->serializerInterface->serialize($user, 'json', SerializationContext::create(["groups" => "read:Users:item"]));
            if (!$user) {
                $response = new Response("Not Found", 404, [
                    "Content-Type" => "application/json"
                ]);
            } else {
                $response = new Response($json, 200, [
                    "Content-Type" => "application/json"
                ]);
            }
        }
        return $response;
    }
    public function __invoke(User $user)
    {
        return $this->findUserByCustomer($user->getId());
    }
}
