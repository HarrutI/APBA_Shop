<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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

    #[ORM\Column]
    private ?string $prize = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img = null;

    /**
     * @var Collection<int, Tags>
     */
    #[ORM\ManyToMany(targetEntity: Tags::class, inversedBy: 'products')]
    private Collection $tags;

    /**
     * @var Collection<int, Materials>
     */
    #[ORM\OneToMany(targetEntity: Materials::class, mappedBy: 'product_id', orphanRemoval: true)]
    private Collection $materials;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, BagProducts>
     */
    #[ORM\OneToMany(targetEntity: BagProducts::class, mappedBy: 'product_id')]
    private Collection $bagProducts;

    /**
     * @var Collection<int, OrderProducts>
     */
    #[ORM\OneToMany(targetEntity: OrderProducts::class, mappedBy: 'product_id')]
    private Collection $orderProducts;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->materials = new ArrayCollection();
        $this->bagProducts = new ArrayCollection();
        $this->orderProducts = new ArrayCollection();
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

    public function getPrize(): ?string
    {
        return $this->prize;
    }

    public function setPrize(string $prize): static
    {
        $this->name = $prize;

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
     * @return Collection<int, Materials>
     */
    public function getMaterials(): Collection
    {
        return $this->materials;
    }

    public function addMaterial(Materials $material): static
    {
        if (!$this->materials->contains($material)) {
            $this->materials->add($material);
            $material->setProductId($this);
        }

        return $this;
    }

    public function removeMaterial(Materials $material): static
    {
        if ($this->materials->removeElement($material)) {
            // set the owning side to null (unless already changed)
            if ($material->getProductId() === $this) {
                $material->setProductId(null);
            }
        }

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

    /**
     * @return Collection<int, BagProducts>
     */
    public function getBagProducts(): Collection
    {
        return $this->bagProducts;
    }

    public function addBagProduct(BagProducts $bagProduct): static
    {
        if (!$this->bagProducts->contains($bagProduct)) {
            $this->bagProducts->add($bagProduct);
            $bagProduct->setProductId($this);
        }

        return $this;
    }

    public function removeBagProduct(BagProducts $bagProduct): static
    {
        if ($this->bagProducts->removeElement($bagProduct)) {
            // set the owning side to null (unless already changed)
            if ($bagProduct->getProductId() === $this) {
                $bagProduct->setProductId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrderProducts>
     */
    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    public function addOrderProducts(OrderProducts $orderProducts): static
    {
        if (!$this->orderProducts->contains($orderProducts)) {
            $this->orderProducts->add($orderProducts);
            $orderProducts->setProductId($this);
        }

        return $this;
    }

    public function removeOrderProducts(OrderProducts $orderProducts): static
    {
        if ($this->orderProducts->removeElement($orderProducts)) {
            // set the owning side to null (unless already changed)
            if ($orderProducts->getProductId() === $this) {
                $orderProducts->setProductId(null);
            }
        }

        return $this;
    }
}
