<?php

namespace App\Controller;

use App\Entity\Brand;

use App\Repository\BrandRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class BrandController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    /**
     * @Route("/brand", name="app_brand_add",methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer): Response
    {
        $data = $request->getContent();
        $brand = $serializer->deserialize($data, "App\Entity\Brand", 'json');
        $this->manager->persist($brand);
        $this->manager->flush();
        return new  Response('', Response::HTTP_CREATED);
    }
    /**
     * @Route("/brand/{id}",name="app_brand_show",methods={"GET"})
     *
     */
    public function show(Brand $brand, SerializerInterface $serializer)
    {
        $data = $serializer->serialize($brand, "json", SerializationContext::create()->setGroups(['detail']));
        $response = new Response($data);
        $response->headers->set("content-type", "application/json");
        return $response;
    }

    /**
     * @Route("/brand",name="app_brand_index",methods={"GET"})
     *
     */
    public function index(BrandRepository $repository, SerializerInterface $serializer)
    {
        $brands = $repository->findAll();
        $data = $serializer->serialize($brands, "json", SerializationContext::create()->setGroups(array('list')));
        $response = new Response($data);
        $response->headers->set("content-type", "application/json");
        return $response;
    }
}
