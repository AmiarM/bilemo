<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserDeleteController extends AbstractController
{
    /**
     *
     * @var EntityManagerInterface
     */
    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager  = $manager;
    }
    /**
     * @Route(
     *     name="route_customers_user_delete",
     *     path="/users/{id}",
     *     defaults={"_api_resource_class"=User::class,
     *               "_api_item_operation_name"="read:Users:item"
     *     }
     * )
     * @Method("DELETE")
     */
    public function deleteUserByCustomer($id)
    {
        $customerConnecte = $this->getUser();
        if ($customerConnecte) {
            $user = $this->manager->getRepository(User::class)->findBy(["id" => $id]);
            $this->manager->remove($user[0], true);
            $this->manager->flush();
        }
    }
    public function __invoke(User $user)
    {
        return $this->deleteUserByCustomer($user->getId());
    }
}
