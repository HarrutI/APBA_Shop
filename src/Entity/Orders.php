<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Clients $Client_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientId(): ?Clients
    {
        return $this->Client_id;
    }

    public function setClientId(?Clients $Client_id): static
    {
        $this->Client_id = $Client_id;

        return $this;
    }
}
