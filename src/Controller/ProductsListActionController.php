<?php

namespace App\Controller;

use ApiPlatform\Core\DataProvider\PaginatorInterface;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Regex;

class ProductsListActionController extends AbstractController
{
    public function __invoke(ProductRepository $productRepository, Request $request)
    {
        $page = (int) $request->query->get('page', 1);
        $products = $productRepository->getProductList($page);
        if (count($products) == 0) {
            return new JsonResponse(["Code" => 200, "Message" => "aucun produit en base de données"], Response::HTTP_OK);
        }
        return $products;
    }
}
