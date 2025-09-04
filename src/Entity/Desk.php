<?php

namespace App\Entity;

use App\Repository\DeskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata as API;

use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

use ApiPlatform\OpenApi\Model\Operation as OAOperation;
use ApiPlatform\OpenApi\Model\Parameter as OAParameter;

#[ORM\Entity(repositoryClass: DeskRepository::class)]
#[ORM\Table(name: 'desk')]
#[ORM\UniqueConstraint(name: 'uniq_desk_num', columns: ['num'])]

#[API\ApiResource(
    shortName: 'Tables',
    operations: [
        new API\GetCollection(
            uriTemplate: '/tables', 
            normalizationContext: ['groups' => ['tables.list'], 'skip_null_values' => false],
            openapi: new OAOperation(
                summary: 'Retrieves the collection of Tables resources.',
                parameters: [
                    new OAParameter(
                        name: 'num',
                        in: 'query',
                        required: false,
                        schema: ['type' => 'integer'],
                    ),
                    new OAParameter(
                        name: 'num[]',
                        in: 'query',
                        required: false,
                        schema: ['type' => 'array', 'items' => ['type' => 'integer']],
                    ),
                ],
            ),
        ),
        new API\Get(
            uriTemplate: '/tables/{id}', 
            normalizationContext: ['groups' => ['tables.item'], 'skip_null_values' => false]
        ),

        new API\Patch(
            uriTemplate: '/tables/{id}',
            denormalizationContext: ['groups' => ['table:patch']],
            inputFormats: ['json' => ['application/json']],
            
        ),

        new API\Get(uriTemplate: '/tables/{id}/guests'),
    ]
)]

class Desk
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: Types::INTEGER), Groups(['tables.list','tables.item'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::INTEGER), Groups(['tables.list','tables.item'])]
    private int $num;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true), Groups(['tables.list','tables.item'])]
    private ?string $description = null;

    #[ORM\Column(name: 'max_guests', type: Types::INTEGER), Groups(['tables.list','tables.item'])]
    private int $maxGuests;

    #[ORM\Column(name: 'guests_def', type: Types::INTEGER, nullable: true), Groups(['tables.list','tables.item'])]
    private ?int $guestsDef = null;

    #[ORM\Column(name: 'guests_now', type: Types::INTEGER, nullable: true), Groups(['tables.list','tables.item'])]
    private ?int $guestsNow = null;
    #[ORM\OneToMany(mappedBy: 'desk', targetEntity: Guest::class)]
    private Collection $guestsList;

    public function __construct() { $this->guestsList = new ArrayCollection(); }
    /** @return Collection<int, Guest> */
    public function getGuestsList(): Collection
    {
        return $this->guestsList;
    }

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

    #[Groups(['desk:get'])]
    #[SerializedName('guests')]
    #[ApiProperty(openapiContext: [
        'type'    => 'array',
        'items'   => ['type' => 'string', 'format' => 'uri'],
        'example' => ['https://example.com/'],
    ])]

    public function getGuestsLinks(): array
    {
        $links = [];
        foreach ($this->guestsList as $g) {
            if (null !== $g->getId()) {
                $links[] = '/api/guest_lists/'.$g->getId();
            }
        }
        return $links;
    }

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
