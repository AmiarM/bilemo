<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Contoller\UsersController;
use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use App\Controller\UsersLinkedCustomerController;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @ApiResource(
 *   normalizationContext={
 *     "groups"={
 *         "read:users",
 *         "opeapi_definition_name"="Collection"
 *      }
 *   },
 *  attributes={
 *          "pagination_items_per_page":10,
 *          "pagination_maximum_items_per_page":10,
 *          "pagination_client_items_per_page":true
 *   },
 *      itemOperations={
 *          "get"={
 *                "normalization_context"={
 *              "groups"={
 *                  "read:customers",
 *                  "read:customer",
 *                  "read:user",
 *              }
 *          },
 *               "openapi_context"={
 *                      "summary"="Consulter le détail d’un utilisateur inscrit lié à un client",
 *                      "description"="Consulter le détail d’un utilisateur inscrit lié à un client"
 *                  }
 *           },
 *           "delete"={
 *              "method":"delete",
 *              "path":"/api/customers/{customer_id}/sers/{user_id}",
 *              "openapi_context"={
 *                      "summary"="Supprimer un utilisateur ajouté par un client",
 *                      "description"="Supprimer un utilisateur ajouté par un client"
 *                }
 *          },
 *      },
 *      collectionOperations={
 *          "post"={
 *              "method":"post",
 *              "path":"/api/customers/{customer_id}/users/{user_id}",
 *              "openapi_context"={
 *                      "summary"="Ajouter un nouvel utilisateur lié à un client ",
 *                      "description"="Ajouter un nouvel utilisateur lié à un client "
 *                }
 *          },
 *          "get"={
 *               "method":"get",
 *               "path":"/customers/{customer_id}/users",
 *               "controller": UsersLinkedCustomerController::class,
 *               "openapi_context"={
 *                      "summary"="Consulter la liste des utilisateurs inscrits liés à un client sur le site web ",
 *                      "description"="Consulter la liste des utilisateurs inscrits liés à un client sur le site web "
 *                }
 *           },
 *      }
 * )
 *
 */
class Customer implements UserInterface, PasswordAuthenticatedUserInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:customer"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"read:customers"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"read:customer"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:customers"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:customers"})
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:customer"})
     */
    private $company;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read:customer"})
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="customer")
     * @Groups({"read:user"})
     */
    private $users;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCustomer($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCustomer() === $this) {
                $user->setCustomer(null);
            }
        }

        return $this;
    }
}
