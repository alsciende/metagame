<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Record of a User authorizing a Client
 *
 * @ORM\Table(name="authorizations")
 * @ORM\Entity()
 *
 * @author Alsciende <alsciende@icloud.com>
 */
class Authorization
{
    use TimestampableEntity;

    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    public function getId (): ?int
    {
        return $this->id;
    }

    public function setId (int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var User|null
     * @ORM\ManyToOne(targetEntity="User", inversedBy="authorizations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    public function setUser(User $user = null): self
    {
        $this->user = $user;
        if ($user instanceof User) {
            $user->addAuthorization($this);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @var Client|null
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="authorizations")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    protected $client;

    public function setClient(Client $client = null): self
    {
        $this->client = $client;
        if ($client instanceof Client) {
            $client->addAuthorization($this);
        }

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }
}