<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    public function __invoke(Request $request)
    {
        $customerConnecte = $this->getUser();
        if (!$customerConnecte) {
            return new JsonResponse(["Code" => 401, "Error" => "Vous devez vous connectez pour accéder à la ressource"], Response::HTTP_UNAUTHORIZED);
        }

        $params = $request->attributes->get('_route_params');
        $id = $params['id'];
        $user = $this->manager->getRepository(User::class)->findBy(["id" => $id]);
        if (count($user) == 0) {
            return new JsonResponse(["Code" => 404, "Error" => "l'utilisateur d'id $id n'existe pas"], Response::HTTP_NOT_FOUND);
        }
        $this->manager->remove($user[0], true);
        $this->manager->flush();
        return new JsonResponse(["Code" => 204, "success" => "l'utilisateur d'id $id est supprimé avec succès"], Response::HTTP_NO_CONTENT);
    }
}
