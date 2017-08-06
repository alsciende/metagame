<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of UserController
 *
 * @author Alsciende <alsciende@icloud.com>
 */
class UserController extends Controller
{
    /**
     * Get all Users
     *
     * @Route("/api/users")
     * @Method("GET")
     */
    public function listAction (Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Access denied.');

        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json; charset=UTF-8');
        $response->setContent($this->get('jms_serializer')->serialize($users, 'json'));

        return $response;
    }

    /**
     * Get myself
     *
     * @Route("/api/users/me")
     * @Method("GET")
     */
    public function getMeAction (Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED', null, 'Access denied.');

        $user = $this->getUser();

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json; charset=UTF-8');
        $response->setContent($this->get('jms_serializer')->serialize($user, 'json'));

        return $response;
    }

    /**
     * Get a User
     *
     * @Route("/api/users/{id}")
     * @Method("GET")
     */
    public function getAction (Request $request, string $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Access denied.');

        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json; charset=UTF-8');
        $response->setContent($this->get('jms_serializer')->serialize($user, 'json'));

        return $response;
    }
}