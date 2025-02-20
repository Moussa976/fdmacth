<?php

namespace App\Entity;

use App\Repository\MatcheRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatcheRepository::class)
 */
class Matche
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $ladate;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity=Equipe::class, inversedBy="matches")
     */
    private $equipe1;

    /**
     * @ORM\ManyToOne(targetEntity=Equipe::class, inversedBy="matches")
     */
    private $equipe2;

    /**
     * @ORM\OneToOne(targetEntity=FeuilleMatch::class, cascade={"persist", "remove"})
     */
    private $feuilleMatch;

    /**
     * @ORM\OneToMany(targetEntity=But::class, mappedBy="matche")
     */
    private $buts;

    /**
     * @ORM\OneToMany(targetEntity=Carton::class, mappedBy="matche")
     */
    private $cartons;

    /**
     * @ORM\OneToMany(targetEntity=Remplacement::class, mappedBy="matche")
     */
    private $remplacements;

    public function __construct()
    {
        $this->buts = new ArrayCollection();
        $this->cartons = new ArrayCollection();
        $this->remplacements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLadate(): ?\DateTimeInterface
    {
        return $this->ladate;
    }

    public function setLadate(\DateTimeInterface $ladate): self
    {
        $this->ladate = $ladate;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getEquipe1(): ?Equipe
    {
        return $this->equipe1;
    }

    public function setEquipe1(?Equipe $equipe1): self
    {
        $this->equipe1 = $equipe1;

        return $this;
    }

    public function getEquipe2(): ?Equipe
    {
        return $this->equipe2;
    }

    public function setEquipe2(?Equipe $equipe2): self
    {
        $this->equipe2 = $equipe2;

        return $this;
    }

    public function getFeuilleMatch(): ?FeuilleMatch
    {
        return $this->feuilleMatch;
    }

    public function setFeuilleMatch(?FeuilleMatch $feuilleMatch): self
    {
        $this->feuilleMatch = $feuilleMatch;

        return $this;
    }

    /**
     * @return Collection<int, But>
     */
    public function getButs(): Collection
    {
        return $this->buts;
    }

    public function addBut(But $but): self
    {
        if (!$this->buts->contains($but)) {
            $this->buts[] = $but;
            $but->setMatche($this);
        }

        return $this;
    }

    public function removeBut(But $but): self
    {
        if ($this->buts->removeElement($but)) {
            // set the owning side to null (unless already changed)
            if ($but->getMatche() === $this) {
                $but->setMatche(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Carton>
     */
    public function getCartons(): Collection
    {
        return $this->cartons;
    }

    public function addCarton(Carton $carton): self
    {
        if (!$this->cartons->contains($carton)) {
            $this->cartons[] = $carton;
            $carton->setMatche($this);
        }

        return $this;
    }

    public function removeCarton(Carton $carton): self
    {
        if ($this->cartons->removeElement($carton)) {
            // set the owning side to null (unless already changed)
            if ($carton->getMatche() === $this) {
                $carton->setMatche(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Remplacement>
     */
    public function getRemplacements(): Collection
    {
        return $this->remplacements;
    }

    public function addRemplacement(Remplacement $remplacement): self
    {
        if (!$this->remplacements->contains($remplacement)) {
            $this->remplacements[] = $remplacement;
            $remplacement->setMatche($this);
        }

        return $this;
    }

    public function removeRemplacement(Remplacement $remplacement): self
    {
        if ($this->remplacements->removeElement($remplacement)) {
            // set the owning side to null (unless already changed)
            if ($remplacement->getMatche() === $this) {
                $remplacement->setMatche(null);
            }
        }

        return $this;
    }

    public function getScoreEquipe1(): int
    {
        return array_reduce($this->getButs()->toArray(), function ($carry, $but) {
            return $but->getJoueur()->getEquipe() === $this->getEquipe1() ? $carry + 1 : $carry;
        }, 0);
    }

    public function getScoreEquipe2(): int
    {
        return array_reduce($this->getButs()->toArray(), function ($carry, $but) {
            return $but->getJoueur()->getEquipe() === $this->getEquipe2() ? $carry + 1 : $carry;
        }, 0);
    }
}
