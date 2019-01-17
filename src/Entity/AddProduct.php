<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AddProductRepository")
 */
class AddProduct
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $amazonLink;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $shortName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmazonLink(): ?string
    {
        return $this->amazonLink;
    }

    public function setAmazonLink(string $amazonLink): self
    {
        $this->amazonLink = $amazonLink;

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }
}
