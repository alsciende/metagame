<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * Description of AccessToken
 *
 * @author Alsciende <alsciende@icloud.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
     * @var string
     * @ORM\Column(name="id", type="string", length=255, unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @var Collection|Authorization[]
     * @ORM\OneToMany(targetEntity="Authorization", mappedBy="user")
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
            $authorization->setUser($this);
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
            $authorization->setUser(null);
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