<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $shortTitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $amazonLink;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $affiliateLink;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cheersLink;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $likes;

    /**
     * @ORM\Column(type="boolean", length=255, nullable=true)
     */
    private $infoComplete;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAffiliateLink(): ?string
    {
        return $this->affiliateLink;
    }

    public function setAffiliateLink(string $affiliateLink): self
    {
        $this->affiliateLink = $affiliateLink;

        return $this;
    }

    public function getCheersLink(): ?string
    {
        return $this->cheersLink;
    }

    public function setCheersLink(string $cheersLink): self
    {
        $this->cheersLink = $cheersLink;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShortTitle()
    {
        return $this->shortTitle;
    }

    /**
     * @param mixed $shortTitle
     */
    public function setShortTitle($shortTitle): void
    {
        $this->shortTitle = $shortTitle;
    }

    /**
     * @return mixed
     */
    public function getAmazonLink()
    {
        return $this->amazonLink;
    }

    /**
     * @param mixed $amazonLink
     */
    public function setAmazonLink($amazonLink): void
    {
        $this->amazonLink = $amazonLink;
    }

    /**
     * @return mixed
     */
    public function getInfoComplete()
    {
        return $this->infoComplete;
    }

    /**
     * @param mixed $infoComplete
     */
    public function setInfoComplete($infoComplete): void
    {
        $this->infoComplete = $infoComplete;
    }

    /**
     * @return mixed
     */
    public function getLikes()
    {
        return $this->likes;
    }

    public function changeLikes($amount): void
    {
        $this->likes = $this->likes + $amount;
    }
}
