<?php

namespace App\Entity;

use App\Repository\BagProductsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BagProductsRepository::class)]
class BagProducts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Bags::class, inversedBy: 'bagProducts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Bags $bag_id = null;

    #[ORM\ManyToOne(targetEntity: Products::class, inversedBy: 'bagProducts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Products $product_id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBagId(): ?Bags
    {
        return $this->bag_id;
    }

    public function setBagId(?Bags $bag_id): static
    {
        $this->bag_id = $bag_id;

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
