<?php

namespace App\Entity;

use App\Repository\FeuilleMatchRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FeuilleMatchRepository::class)
 */
class FeuilleMatch
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $titulairesEquipe1 = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $remplacantsEquipe1 = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $dirigeantsEquipe1 = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $titulairesEquipe2 = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $remplacantsEquipe2 = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $dirigeantsEquipe2 = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $arbitres = [];

    /**
     * @ORM\OneToOne(targetEntity=Matche::class, cascade={"persist", "remove"})
     */
    private $matche;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulairesEquipe1(): ?array
    {
        return $this->titulairesEquipe1;
    }

    public function setTitulairesEquipe1(?array $titulairesEquipe1): self
    {
        $this->titulairesEquipe1 = $titulairesEquipe1;

        return $this;
    }

    public function getRemplacantsEquipe1(): ?array
    {
        return $this->remplacantsEquipe1;
    }

    public function setRemplacantsEquipe1(?array $remplacantsEquipe1): self
    {
        $this->remplacantsEquipe1 = $remplacantsEquipe1;

        return $this;
    }

    public function getDirigeantsEquipe1(): ?array
    {
        return $this->dirigeantsEquipe1;
    }

    public function setDirigeantsEquipe1(?array $dirigeantsEquipe1): self
    {
        $this->dirigeantsEquipe1 = $dirigeantsEquipe1;

        return $this;
    }

    public function getTitulairesEquipe2(): ?array
    {
        return $this->titulairesEquipe2;
    }

    public function setTitulairesEquipe2(?array $titulairesEquipe2): self
    {
        $this->titulairesEquipe2 = $titulairesEquipe2;

        return $this;
    }

    public function getRemplacantsEquipe2(): ?array
    {
        return $this->remplacantsEquipe2;
    }

    public function setRemplacantsEquipe2(?array $remplacantsEquipe2): self
    {
        $this->remplacantsEquipe2 = $remplacantsEquipe2;

        return $this;
    }

    public function getDirigeantsEquipe2(): ?array
    {
        return $this->dirigeantsEquipe2;
    }

    public function setDirigeantsEquipe2(?array $dirigeantsEquipe2): self
    {
        $this->dirigeantsEquipe2 = $dirigeantsEquipe2;

        return $this;
    }

    public function getArbitres(): ?array
    {
        return $this->arbitres;
    }

    public function setArbitres(?array $arbitres): self
    {
        $this->arbitres = $arbitres;

        return $this;
    }

    public function getMatche(): ?Matche
    {
        return $this->matche;
    }

    public function setMatche(?Matche $matche): self
    {
        $this->matche = $matche;

        return $this;
    }
}
