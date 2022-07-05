<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ApiResource(
 *   normalizationContext={
 *     "groups"={
 *         "read:products",
 *         "opeapi_definition_name"="Collection"
 *      },
 *   },
 *   
 *   attributes={
 *          "pagination_items_per_page":10,
 *          "pagination_maximum_items_per_page":10,
 *          "pagination_client_items_per_page":true
 *   },
 *   itemOperations={
 *      "get"={
 *          "normalization_context"={
 *              "groups"={
 *                  "read:products",
 *                  "read:product",
 *                  "read:brand",
 *                  "read:feature"
 *              }
 *          },
 *           "openapi_context"={
 *              "summary"="Consulter les détails d’un produit BILEMO",
 *              "description"="Consulter les détails d’un produit BILEMO"
 *          }
 *      },
 *   },
 *     collectionOperations={
 *         "get"={
 *                  "openapi_context"={
 *                      "summary"="Consulter la liste des produits BILEMO",
 *                      "description"="Consulter la liste des produits BILEMO"
 *                  }
 *          },
 *     }
 * ),
 * @ApiFilter(
 *    SearchFilter::class,properties={
 *        "id":"exact",
 *        "name":"partial"
 *    }
 * )
 */
class Product
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:products"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:products"})
     * @Assert\NotBlank(message="le nom du produit est obligatoire")
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "le nom du brand doit faire entre 3 et 50 caractères",
     *      maxMessage = "le nom du brand doit faire entre 3 et 50 caractères"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Groups({"read:product"})
     * @Assert\NotBlank(message="le nom du produit est obligatoire")
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "le nom du brand doit faire entre 3 et 50 caractères",
     *      maxMessage = "le nom du brand doit faire entre 3 et 50 caractères"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank
     * @Groups({"read:products"})
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read:product"})
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read:product"})
     */
    private $brand;

    /**
     * @ORM\OneToMany(targetEntity=Feature::class, mappedBy="product", orphanRemoval=true)
     * @Groups({"read:feature"})
     */
    private $features;

    public function __construct()
    {
        $this->features = new ArrayCollection();
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection<int, Feature>
     */
    public function getFeatures(): Collection
    {
        return $this->features;
    }

    public function addFeature(Feature $feature): self
    {
        if (!$this->features->contains($feature)) {
            $this->features[] = $feature;
            $feature->setProduct($this);
        }

        return $this;
    }

    public function removeFeature(Feature $feature): self
    {
        if ($this->features->removeElement($feature)) {
            // set the owning side to null (unless already changed)
            if ($feature->getProduct() === $this) {
                $feature->setProduct(null);
            }
        }

        return $this;
    }
}
