<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserDetailController extends AbstractController
{
    /**
     *
     * @var UserRepository
     */
    protected $userRepository;
    /**
     *
     * @var Request
     */
    protected $request;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository  = $userRepository;
    }



    public function __invoke(Request $request)
    {
        $customerConnecte = $this->getUser();
        if (!$customerConnecte) {
            return new JsonResponse(["Code" => 403, "Error" => "Vous devez vous connectez pour accéder à la ressource"], Response::HTTP_FORBIDDEN);
        }
        $params = $request->attributes->get('_route_params');
        $id = $params['id'];
        $user = $this->userRepository->findBy(['id' => $id]);
        if (count($user) == 0) {
            return new JsonResponse(["Code" => 404, "Error" => "l'utilisateur  d'id $id n'existe pas"], Response::HTTP_NOT_FOUND);
        }
        return $user;
    }
}
