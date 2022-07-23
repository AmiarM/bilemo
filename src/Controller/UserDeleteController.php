<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;

class UserDeleteController extends AbstractController
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
     * @Route(
     *     name="route_customers_user_delete",
     *     path="/users/{id}",
     *     defaults={"_api_resource_class"=User::class,
     *               "_api_item_operation_name"="read:Users:collection"
     *     }
     * )
     * @Method("DELETE")
     */
    public function deleteUserByCustomer($id)
    {
        $customerConnecte = $this->getUser();
        if ($customerConnecte) {
            $user = $this->userRepository->findBy([
                'id' => $id
            ]);
            $this->userRepository->remove($user[0], true);
        }
    }
    public function __invoke(User $user)
    {
        return $this->deleteUserByCustomer($user->getId());
    }
}
