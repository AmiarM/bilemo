<?php

namespace App\Serializer;

use App\Entity\User;
use App\Repository\CustomerRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class CustomerDenormalizer implements ContextAwareDenormalizerInterface, DenormalizerAwareInterface
{
    // implements dynamically DenormalizerAwareInterface
    use DenormalizerAwareTrait;

    private const ALREADY_CALLED_DENORMALIZER = 'UserOwnedDenormalizerCalled';

    private $security;
    private $CustomerRepository;

    public function __construct(Security $security, CustomerRepository $CustomerRepository)
    {
        $this->security = $security;
        $this->CustomerRepository = $CustomerRepository;
    }

    // check if we call the denormalize method
    public function supportsDenormalization($data, string $type, ?string $format = null, array $context = [])
    {
        $alreadyCalled = $context[self::ALREADY_CALLED_DENORMALIZER] ?? false;
        return $type === User::class && !$alreadyCalled;
    }

    public function denormalize($data, string $type, ?string $format = null, array $context = [])
    {
        $context[self::ALREADY_CALLED_DENORMALIZER] = true;
        // I don't want to normalize myself so i use the denormalizer interface to do it
        // I just want to add some logic to the array
        // This line call all the denormalizer in an infinite loop, we need to stop the loop if the denormalizer is already in the context
        // This is due to the fact that we call a denormalizer in a denormalizer
        $user = $this->denormalizer->denormalize($data, $type, $format, $context);

        $Customer = $this->CustomerRepository->find($this->security->getUser()->getId());
        $user->setCustomer($Customer); // we can't get directly the security cause we don't have the password in the JWT token

        return $user;
    }
}
