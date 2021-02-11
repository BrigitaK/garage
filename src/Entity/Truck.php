<?php

namespace App\Entity;

use App\Repository\TruckRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TruckRepository::class)
 */
class Truck
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Maker should not be blank.")
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Maker must be at least {{ limit }} characters long.",
     *      maxMessage = "Maker cannot be longer than {{ limit }} characters."
     * )
     */
    private $maker;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="Plate should not be blank.")
     */
    private $plate;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Make_year should not be blank.")
     */
    private $make_year;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Mechanic_notices should not be blank.")
     */
    private $mechanic_notices;

    /**
     * @ORM\Column(type="integer")
     */
    private $mechanic_id;

    /**
     * @ORM\ManyToOne(targetEntity=Mechanic::class, inversedBy="trucks")
     */
    private $mechanic;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaker(): ?string
    {
        return $this->maker;
    }

    public function setMaker(string $maker): self
    {
        $this->maker = $maker;

        return $this;
    }

    public function getPlate(): ?string
    {
        return $this->plate;
    }

    public function setPlate(string $plate): self
    {
        $this->plate = $plate;

        return $this;
    }

    public function getMakeYear(): ?int
    {
        return $this->make_year;
    }

    public function setMakeYear(int $make_year): self
    {
        $this->make_year = $make_year;

        return $this;
    }

    public function getMechanicNotices(): ?string
    {
        return $this->mechanic_notices;
    }

    public function setMechanicNotices(string $mechanic_notices): self
    {
        $this->mechanic_notices = $mechanic_notices;

        return $this;
    }

    public function getMechanicId(): ?int
    {
        return $this->mechanic_id;
    }

    public function setMechanicId(int $mechanic_id): self
    {
        $this->mechanic_id = $mechanic_id;

        return $this;
    }

    public function getMechanic(): ?Mechanic
    {
        return $this->mechanic;
    }

    public function setMechanic(?Mechanic $mechanic): self
    {
        $this->mechanic = $mechanic;

        return $this;
    }
}
