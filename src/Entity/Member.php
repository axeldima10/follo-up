<?php

namespace App\Entity;


use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use App\Repository\MemberRepository;
use JMS\Serializer\Annotation\Groups;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "app_membre_show",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups={"getMembers"})
 * )
 *
 */

/*
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "app_member_delete",
 *          parameters = { "id" = "expr(object.getId())" },
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups={"getMembers"}, excludeIf = "expr(not is_granted('ROLE_ADMINISTRATEUR') and not is_granted('ROLE_MANAGER'))"),
 * )
 *
 * @Hateoas\Relation(
 *      "update",
 *      href = @Hateoas\Route(
 *          "app_member_edit",
 *          parameters = { "id" = "expr(object.getId())" },
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups={"getMembers"}, excludeIf = "expr(not is_granted('ROLE_ADMINISTRATEUR') and not is_granted('ROLE_MANAGER'))"),
 * )
 *
 */
#[ORM\Entity(repositoryClass: MemberRepository::class)]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column] 
    #[Groups(["getMembers"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getMembers"])]
    #[Assert\NotBlank(message: "Le prénom est obligatoire")]
    #[Assert\Length(max: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getMembers"])]
    #[Assert\NotBlank(message: "Le nom est obligatoire")]
    #[Assert\Length(max: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getMembers"])]
    #[Assert\NotBlank(message: "Le téléphone est obligatoire")]
    #[Assert\Length(min: 9, max: 20, minMessage: "Le téléphone est trop court", maxMessage: "Le téléphone est trop long")]
    private ?string $tel = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le quartier est obligatoire")]
    #[Groups(["getMembers"])]
    private ?string $quartier = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La nationalité est obligatoire")]
    #[Groups(["getMembers"])]
    private ?string $nationalite = null;

    #[ORM\Column]
    #[Groups(["getMembers"])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(["getMembers"])]
    #[Assert\NotNull(message: "Le champ 'isMember' est requis")]
    private ?bool $isMember = null;

    #[ORM\Column(type: Types::DATE_MUTABLE,nullable: true)]
    #[Groups(["getMembers"])]
    #[Assert\Type("\DateTimeInterface", message: "Renseignez une date valide")]
    #[Type("DateTime<'d/m/Y'>")]
    private ?\DateTime $memberJoinedDate = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "Le champ 'isBaptized' est requis")]
    #[Groups(["getMembers"])]
    private ?bool $isBaptized = null;

    #[ORM\Column(type: Types::DATE_MUTABLE,nullable: true)]
    #[Groups(["getMembers"])]
    #[Assert\Type("\DateTimeInterface", message: "Renseignez une date valide")]
    #[Type("DateTime<'d/m/Y'>")]
    private ?\DateTime $BaptismDate = null;

    #[ORM\Column]
    #[Groups(["getMembers"])]
    #[Assert\NotNull(message: "Le champ 'hasTransport' est requis")]
    private ?bool $hasTransport = null;

    #[ORM\Column(type: Types::DATE_MUTABLE,nullable: true)]
    #[Groups(["getMembers"])]
    #[Assert\Type("\DateTimeInterface", message: "Renseignez une date valide")]
    #[Type("DateTime<'d/m/Y'>")]
    private ?\DateTime $transportDate = null;

    #[ORM\Column]
    #[Groups(["getMembers"])]
    #[Assert\NotNull(message: "Le champ 'isInHomeCell' est requis")]
    private ?bool $isInHomeCell = null;

    #[ORM\Column(type: Types::DATE_MUTABLE,nullable: true)]
    #[Groups(["getMembers"])]
    #[Assert\Type("\DateTimeInterface", message: "Renseignez une date valide")]
    #[Type("DateTime<'d/m/Y'>")]
    private ?\DateTime $homeCellJoinDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getMembers"])]
    private ?string $observations = null;

    #[ORM\ManyToOne(inversedBy: 'members')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getMembers"])]
    private ?User $createdBy = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profilePhoto = null;


    public function __construct()
    {
        $this->createdAt= new \DateTimeImmutable();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getQuartier(): ?string
    {
        return $this->quartier;
    }

    public function setQuartier(string $quartier): static
    {
        $this->quartier = $quartier;

        return $this;
    }

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function setNationalite(string $nationalite): static
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isMember(): ?bool
    {
        return $this->isMember;
    }

    public function setIsMember(bool $isMember): static
    {
        $this->isMember = $isMember;

        return $this;
    }

    public function getMemberJoinedDate(): ?\DateTime
    {
        return $this->memberJoinedDate;
    }

    public function setMemberJoinedDate(?\DateTime $memberJoinedDate): static
    {
        $this->memberJoinedDate = $memberJoinedDate;

        return $this;
    }

    public function isBaptized(): ?bool
    {
        return $this->isBaptized;
    }

    public function setIsBaptized(bool $isBaptized): static
    {
        $this->isBaptized = $isBaptized;

        return $this;
    }

    public function getBaptismDate(): ?\DateTime
    {
        return $this->BaptismDate;
    }

    public function setBaptismDate(?\DateTime $BaptismDate): static
    {
        $this->BaptismDate = $BaptismDate;

        return $this;
    }

    public function hasTransport(): ?bool
    {
        return $this->hasTransport;
    }

    public function setHasTransport(bool $hasTransport): static
    {
        $this->hasTransport = $hasTransport;

        return $this;
    }

    public function getTransportDate(): ?\DateTime
    {
        return $this->transportDate;
    }

    public function setTransportDate(?\DateTime $transportDate): static
    {
        $this->transportDate = $transportDate;

        return $this;
    }

    public function isInHomeCell(): ?bool
    {
        return $this->isInHomeCell;
    }

    public function setIsInHomeCell(bool $isInHomeCell): static
    {
        $this->isInHomeCell = $isInHomeCell;

        return $this;
    }

    public function getHomeCellJoinDate(): ?\DateTime
    {
        return $this->homeCellJoinDate;
    }

    public function setHomeCellJoinDate(?\DateTime $homeCellJoinDate): static
    {
        $this->homeCellJoinDate = $homeCellJoinDate;

        return $this;
    }

    public function getObservations(): ?string
    {
        return $this->observations;
    }

    public function setObservations(?string $observations): static
    {
        $this->observations = $observations;

        return $this;
    }

   public function getCreatedBy(): ?User
   {
       return $this->createdBy;
   }

   public function setCreatedBy(?User $createdBy): static
   {
       $this->createdBy = $createdBy;

       return $this;
   }

   public function getProfilePhoto(): ?string
   {
       return $this->profilePhoto;
   }

   public function setProfilePhoto(?string $profilePhoto): static
   {
       $this->profilePhoto = $profilePhoto;

       return $this;
   }
}
