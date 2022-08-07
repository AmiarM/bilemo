<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Customer;
use App\Repository\UserRepository;
use App\DataProvider\UserDataProvider;
use App\Repository\CustomerRepository;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserListController extends AbstractController
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


    public function findUsersByCustomer()
    {
        $customerConnecte = $this->getUser();
        if (!$customerConnecte) {
            return new JsonResponse(["Code" => 403, "Error" => "Vous devez vous connectez pour accéder à la ressource"], Response::HTTP_FORBIDDEN);
        }
        $users = $this->userRepository->findBy([
            'customer' => $customerConnecte
        ]);
        if (count($users) == 0) {
            return new JsonResponse(["Code" => 200, "Message" => "aucun utilisateur en base de données"], Response::HTTP_OK);
        }
        return $users;
    }
    public function __invoke(Customer $customer)
    {
        return $this->findUsersByCustomer();
    }
}
