<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection|AccessToken[]
     * @ORM\OneToMany(targetEntity="AccessToken", mappedBy="client")
     */
    private $accessTokens;

    /** @param Collection|AccessToken[] $accessTokens */
    public function setAccessTokens (Collection $accessTokens): self
    {
        $this->clearAccessTokens();
        foreach ($accessTokens as $accessToken) {
            $this->addAccessToken($accessToken);
        }

        return $this;
    }

    public function addAccessToken (AccessToken $accessToken): self
    {
        if ($this->accessTokens->contains($accessToken) === false) {
            $this->accessTokens->add($accessToken);
            $accessToken->setClient($this);
        }

        return $this;
    }

    /** @return Collection|AccessToken[] */
    public function getAccessTokens (): Collection
    {
        return $this->accessTokens;
    }

    public function removeAccessToken (AccessToken $accessToken): self
    {
        if ($this->accessTokens->contains($accessToken)) {
            $this->accessTokens->removeElement($accessToken);
            $accessToken->setClient(null);
        }

        return $this;
    }

    public function clearAccessTokens (): self
    {
        foreach ($this->getAccessTokens() as $accessToken) {
            $this->removeAccessToken($accessToken);
        }
        $this->accessTokens->clear();

        return $this;
    }

    /**
     * @var Collection|AuthCode[]
     * @ORM\OneToMany(targetEntity="AuthCode", mappedBy="client")
     */
    private $authCodes;

    /** @param Collection|AuthCode[] $authCodes */
    public function setAuthCodes (Collection $authCodes): self
    {
        $this->clearAuthCodes();
        foreach ($authCodes as $authCode) {
            $this->addAuthCode($authCode);
        }

        return $this;
    }

    public function addAuthCode (AuthCode $authCode): self
    {
        if ($this->authCodes->contains($authCode) === false) {
            $this->authCodes->add($authCode);
            $authCode->setClient($this);
        }

        return $this;
    }

    /** @return Collection|AuthCode[] */
    public function getAuthCodes (): Collection
    {
        return $this->authCodes;
    }

    public function removeAuthCode (AuthCode $authCode): self
    {
        if ($this->authCodes->contains($authCode)) {
            $this->authCodes->removeElement($authCode);
            $authCode->setClient(null);
        }

        return $this;
    }

    public function clearAuthCodes (): self
    {
        foreach ($this->getAuthCodes() as $authCode) {
            $this->removeAuthCode($authCode);
        }
        $this->authCodes->clear();

        return $this;
    }

    /**
     * @var Collection|RefreshToken[]
     * @ORM\OneToMany(targetEntity="RefreshToken", mappedBy="client")
     */
    private $refreshTokens;

    /** @param Collection|RefreshToken[] $refreshTokens */
    public function setRefreshTokens (Collection $refreshTokens): self
    {
        $this->clearRefreshTokens();
        foreach ($refreshTokens as $refreshToken) {
            $this->addRefreshToken($refreshToken);
        }

        return $this;
    }

    public function addRefreshToken (RefreshToken $refreshToken): self
    {
        if ($this->refreshTokens->contains($refreshToken) === false) {
            $this->refreshTokens->add($refreshToken);
            $refreshToken->setClient($this);
        }

        return $this;
    }

    /** @return Collection|RefreshToken[] */
    public function getRefreshTokens (): Collection
    {
        return $this->refreshTokens;
    }

    public function removeRefreshToken (RefreshToken $refreshToken): self
    {
        if ($this->refreshTokens->contains($refreshToken)) {
            $this->refreshTokens->removeElement($refreshToken);
            $refreshToken->setClient(null);
        }

        return $this;
    }

    public function clearRefreshTokens (): self
    {
        foreach ($this->getRefreshTokens() as $refreshToken) {
            $this->removeRefreshToken($refreshToken);
        }
        $this->refreshTokens->clear();

        return $this;
    }

    /**
     * @var Collection|Authorization[]
     * @ORM\OneToMany(targetEntity="Authorization", mappedBy="client")
     */
    private $authorizations;

    /** @param Collection|Authorization[] $authorizations */
    public function setAuthorizations (Collection $authorizations): self
    {
        $this->clearAuthorizations();
        foreach ($authorizations as $authorization) {
            $this->addAuthorization($authorization);
        }

        return $this;
    }

    public function addAuthorization (Authorization $authorization): self
    {
        if ($this->authorizations->contains($authorization) === false) {
            $this->authorizations->add($authorization);
            $authorization->setClient($this);
        }

        return $this;
    }

    /** @return Collection|Authorization[] */
    public function getAuthorizations (): Collection
    {
        return $this->authorizations;
    }

    public function removeAuthorization (Authorization $authorization): self
    {
        if ($this->authorizations->contains($authorization)) {
            $this->authorizations->removeElement($authorization);
            $authorization->setClient(null);
        }

        return $this;
    }

    public function clearAuthorizations (): self
    {
        foreach ($this->getAuthorizations() as $authorization) {
            $this->removeAuthorization($authorization);
        }
        $this->authorizations->clear();

        return $this;
    }
}
