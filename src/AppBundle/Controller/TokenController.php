<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 */
class TokenController extends Controller
{
    /**
     * Get myself
     *
     * @Route("/api/tokens/me")
     * @Method("GET")
     */
    public function getMeAction ()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED', null, 'Access denied.');

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json; charset=UTF-8');
        $response->setContent($this->get('jms_serializer')->serialize(
            $this
                ->get('fos_oauth_server.access_token_manager')
                ->findTokenByToken(
                    $this
                        ->get('security.token_storage')
                        ->getToken()
                        ->getToken()
                ),
            'json'
        ));

        return $response;
    }
}
