<?php

namespace AppBundle\Entity;

use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Client
 *
 * @author Alsciende <alsciende@icloud.com>
 * 
 * @ORM\Table(name="oauth_clients")
 * @ORM\Entity
 */
class Client extends BaseClient
{
    // disabled client, no access to data
    const LEVEL_NONE = 0;
    // client with read-only access to data
    const LEVEL_BASE = 1;
    // client with read-write access to data
    const LEVEL_HIGH = 2;
    // client with full access to all data
    const LEVEL_FULL = 2;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string",nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="string",nullable=false)
     */
    protected $email;

    /**
     * @ORM\OneToMany(targetEntity="AccessToken", mappedBy="client", cascade={"persist", "remove"})
     * @var AccessToken
     */
    protected $accessTokens;
    
    /**
     * @ORM\OneToMany(targetEntity="AuthCode", mappedBy="client", cascade={"persist", "remove"})
     * @var AuthCode
     */
    protected $authCodes;
    
    /**
     * @ORM\OneToMany(targetEntity="RefreshToken", mappedBy="client", cascade={"persist", "remove"})
     * @var RefreshToken
     */
    protected $refreshTokens;
    
    function getId ()
    {
        return $this->id;
    }

    function getName ()
    {
        return $this->name;
    }

    function getEmail ()
    {
        return $this->email;
    }

    function setName ($name)
    {
        $this->name = $name;
    }

    function setEmail ($email)
    {
        $this->email = $email;
    }

}
