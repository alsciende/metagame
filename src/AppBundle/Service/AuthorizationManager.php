<?php

namespace AppBundle\Service;
use AppBundle\Entity\Authorization;
use AppBundle\Entity\Client;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of AuthorizationManager
 *
 * @author Alsciende <alsciende@icloud.com>
 */
class AuthorizationManager
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct (EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findAuthorization(User $user, Client $client): ?Authorization
    {
        return $this
            ->entityManager
            ->getRepository(Authorization::class)
            ->findOneBy([
                'user' => $user,
                'client' => $client,
            ]);
    }

    public function isClientAuthorized(User $user, Client $client): bool
    {
        return $this->findAuthorization($user, $client) instanceof Authorization;
    }

    public function createAuthorization(User $user, Client $client): Authorization
    {
        $authorization = new Authorization();
        $authorization->setClient($client);
        $authorization->setUser($user);
        $this->entityManager->persist($authorization);
        $this->entityManager->flush();

        return $authorization;
    }
}