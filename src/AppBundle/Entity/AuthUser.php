<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Description of AuthUser
 *
 * @ORM\Entity()
 * @ORM\Table(name="auth_users")
 *
 * @author Alsciende <alsciende@icloud.com>
 */
class AuthUser
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
     * @var integer
     *
     * @ORM\Column()
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("id")
     */
    private $externalId;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Serializer\Expose()
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Serializer\Expose()
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(type="string")
     *
     * @Serializer\Expose()
     */
    private $reputation;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Serializer\Expose()
     */
    private $server;

    /**
     * @var AuthToken|null
     * @ORM\OneToOne(targetEntity="AuthToken", inversedBy="authUser")
     * @ORM\JoinColumn(name="auth_token_id", referencedColumnName="id")
     *
     * @Serializer\Exclude()
     */
    protected $authToken;

    public function setAuthToken (AuthToken $authToken = null): self
    {
        $this->authToken = $authToken;
        if ($authToken instanceof AuthToken) {
            $authToken->setAuthUser($this);
        }

        return $this;
    }

    public function getAuthToken (): ?AuthToken
    {
        return $this->authToken;
    }

    public function getId (): int
    {
        return $this->id;
    }

    public function getExternalId (): int
    {
        return $this->externalId;
    }

    public function setExternalId (int $externalId): self
    {
        $this->externalId = $externalId;

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

    public function getUsername (): string
    {
        return $this->username;
    }

    public function setUsername (string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail (): string
    {
        return $this->email;
    }

    public function setEmail (string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getReputation (): int
    {
        return $this->reputation;
    }

    public function setReputation (int $reputation): self
    {
        $this->reputation = $reputation;

        return $this;
    }
}