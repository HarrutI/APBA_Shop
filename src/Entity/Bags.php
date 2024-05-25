<?php

namespace App\Entity;

use App\Repository\BagsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BagsRepository::class)]
class Bags
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'bags', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user_id = null;

    /**
     * @var Collection<int, BagProducts>
     */
    #[ORM\OneToMany(targetEntity: BagProducts::class, mappedBy: 'bag_id')]
    private Collection $bagProducts;

    public function __construct()
    {
        $this->bagProducts = new ArrayCollection();
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

    public function getUserId(): ?Users
    {
        return $this->user_id;
    }

    public function setUserId(Users $user_id): static
    {
        $this->user_id = $user_id;

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
            $bagProduct->setBagId($this);
        }

        return $this;
    }

    public function removeBagProduct(BagProducts $bagProduct): static
    {
        if ($this->bagProducts->removeElement($bagProduct)) {
            // set the owning side to null (unless already changed)
            if ($bagProduct->getBagId() === $this) {
                $bagProduct->setBagId(null);
            }
        }

        return $this;
    }

}
