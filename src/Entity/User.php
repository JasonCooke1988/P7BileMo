<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ExclusionPolicy("all")
 * @Hateoas\Relation (
 *     "list",
 *     href = @Hateoas\Route(
 *          "app_user_list",
 *          absolute = true
 *      ),
 *     exclusion = @Hateoas\Exclusion(groups={"detail","listClient","all"})
 * )
 * @Hateoas\Relation (
 *     "listByClient",
 *     href = @Hateoas\Route(
 *          "app_client_user_list",
 *          parameters={"id" = "expr(object.getClient().getId())"},
 *          absolute = true
 *      ),
 *     exclusion = @Hateoas\Exclusion(groups={"detail","list","all"})
 * )
 * @Hateoas\Relation (
 *     "self",
 *     href = @Hateoas\Route(
 *          "app_user_show",
 *          parameters={"id" = "expr(object.getId())"},
 *          absolute = true
 *      ),
 *     exclusion = @Hateoas\Exclusion(groups={"list","listClient","all"}),
 * )
 * @Hateoas\Relation (
 *     "delete",
 *     href = @Hateoas\Route(
 *          "app_user_delete",
 *          parameters={"id" = "expr(object.getId())"},
 *          absolute = true
 *      ),
 *     exclusion = @Hateoas\Exclusion(groups={"list","detail","listClient","all"})
 * )
 * @Hateoas\Relation (
 *     "create",
 *     href = @Hateoas\Route(
 *          "app_user_create",
 *          absolute = true,
 *          parameters={"id" = "expr(object.getClient().getId())"},
 *      ),
 *     exclusion = @Hateoas\Exclusion(groups={"detail","list","listClient","all"})
 * )
 * @Hateoas\Relation (
 *     "client",
 *     embedded = "expr(object.getClient())",
 *     exclusion = @Hateoas\Exclusion(groups={"detail","list","listClient","all"})
 * )
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     * @Serializer\Groups({"list","listClient","detail","all"})
     * @Serializer\Since("1.0")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\Groups({"list","listClient","detail","all"})
     * @Serializer\Since("1.0")
     * @Assert\NotBlank(groups={"Create"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=client::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Expose()
     * @Serializer\Since("1.0")
     */
    private $client;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\Groups({"detail","all"})
     * @Serializer\Since("1.0")
     * @Assert\NotBlank(groups={"Create"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\Groups({"detail","all"})
     * @Serializer\Since("1.0")
     * @Assert\NotBlank(groups={"Create"})
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\Groups({"detail","all"})
     * @Serializer\Since("1.0")
     * @Assert\NotBlank(groups={"Create"})
     * @Assert\Email(groups={"Create"},message="The email {{ value }} is not a valid email.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\Groups({"detail","all"})
     * @Serializer\Since("1.0")
     * @Assert\NotBlank(groups={"Create"})
     */
    private $postCode;

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

    public function getClient(): ?client
    {
        return $this->client;
    }

    public function setClient(?client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
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

    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    public function setPostCode(string $postCode): self
    {
        $this->postCode = $postCode;

        return $this;
    }
}
