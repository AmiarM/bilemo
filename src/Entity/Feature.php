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
    protected $id;
    /**
     * @ORM\Column(type="integer")
     * @Groups({"read:products:item"})
     */
    protected $memory;
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:products:item"})
     */
    protected $color;
    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read:products:item"})
     */
    protected $bluetooth;
    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read:products:item"})
     */
    protected $camera;
    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read:products:item"})
     */
    protected $wifi;
    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read:products:item"})
     */
    protected $video4k;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="features")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $product;

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

    public function getBluetooth(): ?bool
    {
        return $this->bluetooth;
    }

    public function setBluetooth(bool $bluetooth): self
    {
        $this->bluetooth = $bluetooth;

        return $this;
    }



    public function getCamera(): ?bool
    {
        return $this->camera;
    }

    public function setCamera(bool $camera): self
    {
        $this->camera = $camera;

        return $this;
    }

    public function getWifi(): ?bool
    {
        return $this->wifi;
    }

    public function setWifi(bool $wifi): self
    {
        $this->wifi = $wifi;

        return $this;
    }

    public function getVideo4k(): ?bool
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
