<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class Products
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img = null;

    #[ORM\ManyToOne(targetEntity: Products::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Materials $material = null;

    /**
     * @var Collection<int, Tags>
     */
    #[ORM\ManyToMany(targetEntity: Tags::class, inversedBy: 'products')]
    private Collection $tags;

    /**
     * @var Collection<int, Orders>
     */
    #[ORM\ManyToMany(targetEntity: Orders::class, mappedBy: 'products')]
    private Collection $orders;

    /**
     * @var Collection<int, Bags>
     */
    #[ORM\ManyToMany(targetEntity: Bags::class, mappedBy: 'products')]
    private Collection $bags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->bags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
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

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): static
    {
        $this->img = $img;

        return $this;
    }

    public function getMaterial(): ?Materials
    {
        return $this->material;
    }

    public function setMaterial(?Materials $material): static
    {
        $this->material = $material;

        return $this;
    }

    /**
     * @return Collection<int, Tags>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tags $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection<int, Orders>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Orders $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->addProduct($this);
        }

        return $this;
    }

    public function removeOrder(Orders $order): static
    {
        if ($this->orders->removeElement($order)) {
            $order->removeProduct($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Bags>
     */
    public function getBags(): Collection
    {
        return $this->bags;
    }

    public function addBag(Bags $bag): static
    {
        if (!$this->bags->contains($bag)) {
            $this->bags->add($bag);
            $bag->addProduct($this);
        }

        return $this;
    }

    public function removeBag(Bags $bag): static
    {
        if ($this->bags->removeElement($bag)) {
            $bag->removeProduct($this);
        }

        return $this;
    }
}
