<?php

namespace App\Entity;

use App\Repository\GuestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;
use App\Entity\Desk;

#[ORM\Entity(repositoryClass: GuestRepository::class)]
#[ORM\Table(name: 'guest')]
#[ORM\Index(columns: ['desk_id'], name: 'idx_guest_desk')]

class Guest
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(name: 'full_name', type: Types::STRING, length: 255)]
    private string $name = '';

    #[ORM\Column(name: 'is_present', type: Types::BOOLEAN, options: ['default' => 0])]
    private bool $isPresent = false;

    // У Стола много Гостей
    #[ORM\ManyToOne(targetEntity: Desk::class, inversedBy: 'guestsList')]
    #[ORM\JoinColumn(
        name: 'desk_id',
        referencedColumnName: 'id',
        nullable: true,           // false -> если стол обязателен
        onDelete: 'SET NULL'      // Удаляем стол -> у гостя обнуляется desk_id
    )]
    #[SerializedName('tables')]
    private ?Desk $desk = null;    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isPresent(): ?bool
    {
        return $this->isPresent;
    }

    public function setIsPresent(bool $isPresent): static
    {
        $this->isPresent = $isPresent;

        return $this;
    }

    public function getDesk(): ?Desk { return $this->desk; }
    public function setDesk(?Desk $desk): static { $this->desk = $desk; return $this; }
}
