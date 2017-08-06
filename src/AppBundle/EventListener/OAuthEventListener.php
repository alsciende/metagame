<?php

namespace AppBundle\EventListener;
use AppBundle\Entity\Client;
use AppBundle\Entity\User;
use AppBundle\Service\AuthorizationManager;
use FOS\OAuthServerBundle\Event\OAuthEvent;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;

/**
 * Description of OAuthEventListener
 *
 * @author Alsciende <alsciende@icloud.com>
 */
class OAuthEventListener
{
    /** @var AuthorizationManager */
    private $authorizationManager;

    /** @var UserManagerInterface */
    private $userManager;

    public function __construct (AuthorizationManager $authorizationManager, UserManagerInterface $userManager)
    {
        $this->authorizationManager = $authorizationManager;
        $this->userManager = $userManager;
    }

    public function onPreAuthorizationProcess(OAuthEvent $event)
    {
        $user = $this->getUser($event);
        $client = $event->getClient();

        if ($user instanceof User && $client instanceof Client) {
            $event->setAuthorizedClient(
                $this->authorizationManager->isClientAuthorized($user, $client)
            );
        }
    }

    public function onPostAuthorizationProcess(OAuthEvent $event)
    {
        if ($event->isAuthorizedClient()) {
            $user = $this->getUser($event);
            $client = $event->getClient();

            if ($user instanceof User && $client instanceof Client) {
                $this->authorizationManager->createAuthorization($user, $client);
            }
        }
    }

    protected function getUser(OAuthEvent $event): ?UserInterface
    {
        return $this->userManager->findUserByUsername($event->getUser()->getUsername());
    }
}