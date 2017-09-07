<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AuthToken;
use AppBundle\Entity\AuthUser;
use GuzzleHttp\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Description of AuthController
 *
 * @author Alsciende <alsciende@icloud.com>
 *
 * @Route(path="/auth")
 */
class AuthController extends Controller
{
    /**
     * @Route("/initiate/{server}", name="oauth2_initiate")
     */
    public function initiateAction (Request $request, string $server)
    {
        if (!$this->isServerValid($server)) {
            throw new NotFoundHttpException();
        }

        $url = $this->getServerUrl($server, '/oauth/v2/auth', [
            'client_id'     => $this->getOauthParameter($server, 'client_id'),
            'response_type' => 'code',
            'redirect_uri'  => $this->getRedirectUri($server),
        ]);

        return new RedirectResponse($url);
    }

    /**
     * @Route(path="/callback/{server}", name="oauth2_callback")
     */
    public function callbackAction (Request $request, string $server)
    {
        if (!$this->isServerValid($server)) {
            throw new NotFoundHttpException();
        }

        $request->getSession()->start();

        // receive the authorization code
        $code = $request->get('code');

        // request the access-token to the oauth server
        $url = $this->getServerUrl($server, '/oauth/v2/token', [
            'client_id'     => $this->getOauthParameter($server, 'client_id'),
            'client_secret' => $this->getOauthParameter($server, 'client_secret'),
            'redirect_uri'  => $this->getRedirectUri($server),
            'grant_type'    => 'authorization_code',
            'code'          => $code,
        ]);

        $client = new Client();
        $res = $client->request('GET', $url);
        if ($res->getStatusCode() !== 200) {
            throw new \Exception($res->getReasonPhrase());
        }

        // process the response
        $response = json_decode($res->getBody(), true);

        /** @var AuthToken $authToken */
        $authToken = $this->get('jms_serializer')->fromArray($response, AuthToken::class);
        $authToken->setServer($server);
        $this->get('doctrine.orm.entity_manager')->persist($authToken);
        $this->get('doctrine.orm.entity_manager')->flush();
        $request->getSession()->set('auth_token', $authToken->getId());

        // request the access-token to the oauth server
        $url = $this->getServerUrl($server, $this->getOauthParameter($server, 'profile_api'), [
            'access_token' => $authToken->getAccessToken(),
        ]);

        $client = new Client();
        $res = $client->request('GET', $url);
        if ($res->getStatusCode() !== 200) {
            throw new \Exception($res->getReasonPhrase());
        }

        // process the response
        $response = json_decode($res->getBody(), true);
        $data = reset($response['data']);

        /** @var AuthUser $authUser */
        $authUser = $this->get('jms_serializer')->fromArray($data, AuthUser::class);
        $authUser->setServer($server);
        $authUser->setAuthToken($authToken);
        $this->get('doctrine.orm.entity_manager')->persist($authUser);
        $this->get('doctrine.orm.entity_manager')->flush();
        $request->getSession()->set('auth_user', $authUser->getId());

        return new RedirectResponse($this->generateUrl('fos_user_registration_register'));
    }

    private function isServerValid (string $server)
    {
        return in_array($server, $this->getParameter('oauth_known_servers'));
    }

    private function getRedirectUri (string $server)
    {
        return $this->generateUrl('oauth2_callback', ['server' => $server], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    private function getOauthParameter (string $server, string $parameter)
    {
        return $this->getParameter('oauth_' . $server . '_' . $parameter);
    }

    private function getServerUrl (string $server, string $path, array $parameters = [])
    {
        return $this->getParameter('oauth_' . $server . '_url') . $path . '?' . http_build_query($parameters);
    }
}