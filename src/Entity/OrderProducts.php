<?php

namespace App\Entity;

use App\Repository\OrderProductsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderProductsRepository::class)]
class OrderProducts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Orders::class, inversedBy: 'orderProducts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Orders $order_id = null;

    #[ORM\ManyToOne(targetEntity: Products::class,inversedBy: 'quantity')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Products $product_id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderId(): ?Orders
    {
        return $this->order_id;
    }

    public function setOrderId(?Orders $order_id): static
    {
        $this->order_id = $order_id;

        return $this;
    }

    public function getProductId(): ?Products
    {
        return $this->product_id;
    }

    public function setProductId(?Products $product_id): static
    {
        $this->product_id = $product_id;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }
}
