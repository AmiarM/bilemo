<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;

class UserDetailController extends AbstractController
{
    /**
     *
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository  = $userRepository;
    }
    /**
     * @Route("/user/detail", name="app_user_detail")
     */
    /**
     * @Route(
     *     name="route_customers_user",
     *     path="/users/{id}",
     *     defaults={"_api_resource_class"=User::class,
     *               "_api_item_operation_name"="read:Users:collection"
     *     }
     * )
     * @Method("GET")
     */
    public function findUserByCustomer($id)
    {
        dd('ok');
        $customerConnecte = $this->getUser();
        dd($customerConnecte);
        if ($customerConnecte) {
            $user = $this->userRepository->findBy(['id' => $id]);
            dd($user);
        }

        return $this->json($user, 200, ["Accept" => "application/json"], ["groups" => "read:Users:collection"]);
    }
    public function __invoke(User $user)
    {
        return $this->findUserByCustomer($user->getId());
    }
}
