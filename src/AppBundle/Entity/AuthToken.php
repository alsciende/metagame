<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Description of AuthToken
 *
 * @ORM\Entity()
 * @ORM\Table(name="auth_tokens")
 *
 * @author Alsciende <alsciende@icloud.com>
 */
class AuthToken
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Exclude()
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Serializer\Expose()
     * @Serializer\Type("string")
     */
    private $accessToken;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     *
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     */
    private $expiresIn;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Serializer\Expose()
     * @Serializer\Type("string")
     */
    private $tokenType;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Serializer\Expose()
     * @Serializer\Type("string")
     */
    private $scope;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Serializer\Expose()
     * @Serializer\Type("string")
     */
    private $refreshToken;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Serializer\Expose()
     * @Serializer\Type("string")
     */
    private $server;

    /**
     * @var AuthUser|null
     *
     * @ORM\OneToOne(targetEntity="AuthUser", mappedBy="authToken")
     *
     * @Serializer\Exclude()
     */
    protected $authUser;

    public function getId (): int
    {
        return $this->id;
    }

    public function setAuthUser (AuthUser $authUser = null): self
    {
        $this->authUser = $authUser;

        return $this;
    }

    public function getAuthUser (): ?AuthUser
    {
        return $this->authUser;
    }

    public function getAccessToken (): string
    {
        return $this->accessToken;
    }

    public function setAccessToken (string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getExpiresIn (): int
    {
        return $this->expiresIn;
    }

    public function setExpiresIn (int $expiresIn): self
    {
        $this->expiresIn = $expiresIn;

        return $this;
    }

    public function getTokenType (): string
    {
        return $this->tokenType;
    }

    public function setTokenType (string $tokenType): self
    {
        $this->tokenType = $tokenType;

        return $this;
    }

    public function getScope (): string
    {
        return $this->scope;
    }

    public function setScope (string $scope): self
    {
        $this->scope = $scope;

        return $this;
    }

    public function getRefreshToken (): string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken (string $refreshToken): self
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function getServer (): string
    {
        return $this->server;
    }

    public function setServer (string $server): self
    {
        $this->server = $server;

        return $this;
    }
}