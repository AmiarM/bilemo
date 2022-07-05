<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FeatureRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FeatureRepository::class)
 */
class Feature
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="integer")
     * @Groups({"read:feature"})
     */
    private $memory;
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:feature"})
     */
    private $color;
    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read:feature"})
     */
    private $bluetooth;
    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read:feature"})
     */
    private $camera;
    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read:feature"})
     */
    private $wifi;
    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read:feature"})
     */
    private $video4k;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="features")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMemory(): ?int
    {
        return $this->memory;
    }

    public function setMemory(int $memory): self
    {
        $this->memory = $memory;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function isBluetooth(): ?bool
    {
        return $this->bluetooth;
    }

    public function setBluetooth(bool $bluetooth): self
    {
        $this->bluetooth = $bluetooth;

        return $this;
    }



    public function isCamera(): ?bool
    {
        return $this->camera;
    }

    public function setCamera(bool $camera): self
    {
        $this->camera = $camera;

        return $this;
    }

    public function isWifi(): ?bool
    {
        return $this->wifi;
    }

    public function setWifi(bool $wifi): self
    {
        $this->wifi = $wifi;

        return $this;
    }

    public function isVideo4k(): ?bool
    {
        return $this->video4k;
    }

    public function setVideo4k(bool $video4k): self
    {
        $this->video4k = $video4k;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
