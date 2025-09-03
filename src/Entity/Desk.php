<?php

namespace App\Entity;

use App\Repository\DeskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeskRepository::class)]
#[ORM\Table(name: 'desk')]
#[ORM\UniqueConstraint(name: 'uniq_desk_num', columns: ['num'])]

class Desk
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::INTEGER)]
    private int $num;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: 'max_guests', type: Types::INTEGER)]
    private int $maxGuests;

    #[ORM\Column(name: 'guests_def', type: Types::INTEGER, nullable: true)]
    private ?int $guestsDef = null;

    #[ORM\Column(name: 'guests_now', type: Types::INTEGER, nullable: true)]
    private ?int $guestsNow = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $guests = null;

    #[ORM\OneToMany(mappedBy: 'desk', targetEntity: Guest::class)]
    private Collection $guestsList;

    public function __construct() { $this->guestsList = new ArrayCollection(); }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNum(): ?int
    {
        return $this->num;
    }

    public function setNum(int $num): static
    {
        $this->num = $num;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMaxGuests(): ?int
    {
        return $this->maxGuests;
    }

    public function setMaxGuests(int $maxGuests): static
    {
        $this->maxGuests = $maxGuests;

        return $this;
    }

    public function getGuestsDef(): ?int
    {
        return $this->guestsDef;
    }

    public function setGuestsDef(?int $guestsDef): static
    {
        $this->guestsDef = $guestsDef;

        return $this;
    }

    public function getGuestsNow(): ?int
    {
        return $this->guestsNow;
    }

    public function setGuestsNow(?int $guestsNow): static
    {
        $this->guestsNow = $guestsNow;

        return $this;
    }

    public function getGuests(): ?array { return $this->guests; }
    public function setGuests(?array $guests): static { $this->guests = $guests; return $this; }

    /** @return Collection<int, Guest> */
    public function getGuestsList(): Collection { return $this->guestsList; }

    public function addGuest(Guest $guest): static
    {
        if (!$this->guestsList->contains($guest)) {
            $this->guestsList->add($guest);
            $guest->setDesk($this);
        }
        return $this;
    }

    public function removeGuest(Guest $guest): static
    {
        if ($this->guestsList->removeElement($guest) && $guest->getDesk() === $this) {
            $guest->setDesk(null);
        }
        return $this;
    }

    public function __toString(): string
    {
        return 'Стол ' . (string)$this->num;
    }
}
