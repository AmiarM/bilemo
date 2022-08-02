<?php

namespace App\DataPersister;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

class UserDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     *
     * @var  UserRepository
     */
    protected $repository;
    /**
     *
     * @var EntityManagerInterface
     */
    protected $entityManager;
    /**
     *
     * @var DataPersisterInterface
     */
    protected $decorated;
    public function __construct(UserRepository $repository, EntityManagerInterface $entityManager, DataPersisterInterface $decorated)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }


    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function persist($data, array $context = [])
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data, $context);
        $this->entityManager->flush();
    }
}
