<?php

namespace App\Entity;

use App\Repository\ProductsOrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsOrdersRepository::class)]
class ProductsOrders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'productsOrders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?orders $orders_id = null;

    /**
     * @var Collection<int, products>
     */
    #[ORM\OneToMany(targetEntity: products::class, mappedBy: 'productsorders')]
    private Collection $products_id;

    #[ORM\Column]
    private ?int $quantity = null;

    public function __construct()
    {
        $this->products_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrdersId(): ?orders
    {
        return $this->orders_id;
    }

    public function setOrdersId(?orders $orders_id): static
    {
        $this->orders_id = $orders_id;

        return $this;
    }

    /**
     * @return Collection<int, products>
     */
    public function getProductsId(): Collection
    {
        return $this->products_id;
    }

    public function addProductsId(products $productsId): static
    {
        if (!$this->products_id->contains($productsId)) {
            $this->products_id->add($productsId);
            $productsId->setProductsorders($this);
        }

        return $this;
    }

    public function removeProductsId(products $productsId): static
    {
        if ($this->products_id->removeElement($productsId)) {
            // set the owning side to null (unless already changed)
            if ($productsId->getProductsorders() === $this) {
                $productsId->setProductsorders(null);
            }
        }

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
