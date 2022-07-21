<?php


namespace App\Doctrine;


use Doctrine\ORM\QueryBuilder;
use App\Entity\UserOwnedInterface;
use App\Entity\CustomerOwnedInterface;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    /**
     * @var Security
     */
    protected $security;
    /**
     * @var AuthorizationCheckerInterface
     */
    protected $checker;
    public function __construct(Security $security, AuthorizationCheckerInterface $checker)
    {
        $this->security = $security;
        $this->checker = $checker;
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ) {
        $this->addWhere($resourceClass, $queryBuilder);
    }

    public function applyToItem(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        string $operationName = null,
        array $context = []
    ) {
        $this->addWhere($resourceClass, $queryBuilder);
    }

    protected function addWhere(string $resourceClass, QueryBuilder $queryBuilder)
    {
        $reflectionClass = new \ReflectionClass($resourceClass);
        if ($reflectionClass->implementsInterface(CustomerOwnedInterface::class) && !$this->checker->isGranted('ROLE_ADMIN')) {
            $alias = $queryBuilder->getRootAliases()[0];
            $customer =  $this->security->getUser();
            if ($customer) {
                $queryBuilder
                    ->andWhere("$alias.customer = :current_customer")
                    ->setParameter('current_customer', $customer->getId());
            } else {
                $queryBuilder->andWhere("$alias.customer IS NULL");
            }
        }
    }
}
