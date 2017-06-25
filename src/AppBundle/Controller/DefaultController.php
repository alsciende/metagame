<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of DefaultController
 *
 * @author Alsciende <alsciende@icloud.com>
 */
class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     * 
     */
    public function indexAction ()
    {
        return new Response("<h1>Metagame.cards</h1>");
    }

    /**
     * @Route("/profile")
     * @Security("has_role('ROLE_USER')")
     *
     */
    public function profileAction ()
    {
        $user = $this->getUser();
        return new Response("<h1>Profile of " . $user->getUsername() . "</h1>");
    }

}
