<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\AuthUser;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Description of RegistrationListener
 *
 * @author Alsciende <alsciende@icloud.com>
 */
class RegistrationListener implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    private $manager;

    public function __construct (EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents ()
    {
        return [
            FOSUserEvents::REGISTRATION_INITIALIZE => 'onRegistrationInitialize',
        ];
    }

    public function onRegistrationInitialize (GetResponseUserEvent $event)
    {
        $session = $event->getRequest()->getSession();
        if ($session instanceof SessionInterface && $session->has('auth_user')) {
            $id = $session->get('auth_user');
            $authUser = $this->manager->getRepository(AuthUser::class)->find($id);

            /** @var User $user */
            $user = $event->getUser();
            $user->setEmail($authUser->getEmail());
            $user->setUsername($authUser->getUsername());
            $user->addAuthUser($authUser);
        }
    }
}