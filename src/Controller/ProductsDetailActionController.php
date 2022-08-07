<?php

namespace App\Controller;

use App\Entity\Feature;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductsDetailActionController extends AbstractController
{
    /**
     *
     * @var ProductRepository
     */
    private $repository;
    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }
    public function __invoke(Request $request)
    {
        $params = $request->attributes->get('_route_params');
        $id = $params['id'];
        $product = $this->repository->findBy([
            'id' => $id
        ]);
        if (count($product) == 0) {
            return new JsonResponse(["Code" => 404, "Error" => "le produit d'id $id n'existe pas"], Response::HTTP_NOT_FOUND);
        }
        return $product;
    }
}
