<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DanielProductsRepository")
 */
class DanielProducts
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
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $offers;

    /**
     * @ORM\Column(type="float")
     */
    private $priceFrom;

    /**
     * @ORM\Column(type="float")
     */
    private $priceTo;

    /**
     * @ORM\Column(type="integer")
     */
    private $page;

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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getOffers(): ?int
    {
        return $this->offers;
    }

    public function setOffers(?int $offers): self
    {
        $this->offers = $offers;

        return $this;
    }

    public function getPriceFrom(): ?float
    {
        return $this->priceFrom;
    }

    public function setPriceFrom(float $priceFrom): self
    {
        $this->priceFrom = $priceFrom;

        return $this;
    }

    public function getPriceTo(): ?float
    {
        return $this->priceTo;
    }

    public function setPriceTo(float $priceTo): self
    {
        $this->priceTo = $priceTo;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page): void
    {
        $this->page = $page;
    }

}
