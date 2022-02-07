<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProgramRepository;
use Doctrine\Common\Collections\Collection;
//Ici on importe le package Vich, que l’on utilisera sous l’alias “Vich”
use Symfony\Component\HttpFoundation\File\File;


use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ProgramRepository::class)
 * @UniqueEntity("title")
 * //On précise à l’entité que nous utiliserons l’upload du package Vich uploader
 * @Vich\Uploadable
 */
class Program
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez remplir ce champ.")
     * @Assert\Length(max="255", maxMessage="Le titre saisi {{ value }} est trop long, il ne doit pas dépasser {{ limit }} caractères")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Veuillez remplir ce champ.")
     * @Assert\Regex(
     *     pattern="/plus belle la vie/",
     *     match=false,
     *     message="On parle de vraies séries ici"
     * )
     */
    private $summary;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $poster;

    //On va créer un nouvel attribut à notre entité, qui ne sera pas lié à une colonne
    // Tu peux d’ailleurs voir que l’annotation ORM column n’est pas spécifiée car
    //On ne rajoute pas de données de type file en bdd
    /**
    * @Vich\UploadableField(mapping="poster_file", fileNameProperty="poster")
    * @Assert\File(
    *     maxSize = "1M",
    *     mimeTypes = {"image/jpeg", "image/png", "image/webp"},
    * )
    * @var File
    */
    private $posterFile;

    /**
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"} )
     */
    private DateTimeInterface $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="programs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Actor::class, inversedBy="programs")
     */
    private $actor;

    /**
     * @ORM\OneToMany(targetEntity=Season::class, mappedBy="program", orphanRemoval=true)
     */
    private $seasons;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="programs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="watchlist")
     */
    private $viewers;

    public function __construct()
    {
        $this->actor = new ArrayCollection();
        $this->seasons = new ArrayCollection();
        $this->viewers = new ArrayCollection();
    }


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

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Actor[]
     */
    public function getActor(): Collection
    {
        return $this->actor;
    }

    public function addActor(Actor $actor): self
    {
        if (!$this->actor->contains($actor)) {
            $this->actor[] = $actor;
            $actor->addProgram($this);
        }

        return $this;
    }

    public function removeActor(Actor $actor): self
    {
        if ($this->actor->removeElement($actor)) {
            $actor->removeProgram($this);
        }
        return $this;
    }

    /**
     * @return Collection|Season[]
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): self
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons[] = $season;
            $season->setProgram($this);
        }

        return $this;
    }

    public function removeSeason(Season $season): self
    {
        if ($this->seasons->removeElement($season)) {
            // set the owning side to null (unless already changed)
            if ($season->getProgram() === $this) {
                $season->setProgram(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getViewers(): Collection
    {
        return $this->viewers;
    }

    public function addViewer(User $viewer): self
    {
        if (!$this->viewers->contains($viewer)) {
            $this->viewers[] = $viewer;
            $viewer->addWatchlist($this);
        }

        return $this;
    }

    public function removeViewer(User $viewer): self
    {
        if ($this->viewers->removeElement($viewer)) {
            $viewer->removeWatchlist($this);
        }

        return $this;
    }

    /**
     * Get the value of posterFile
     */ 
    public function getPosterFile(): ?File
    {
        return $this->posterFile;
    }

    /**
     * Set the value of posterFile
     */ 
    public function setPosterFile(File $image = null)
    {
        $this->posterFile = $image;
        if ($image) {
        $this->updatedAt = new DateTime('now');
        }
    }

    /**
     * Get the value of updatedAt
     */ 
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     */ 
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
