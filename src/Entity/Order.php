<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\OneToMany(targetEntity: ProductsOrders::class, mappedBy: 'order')]
    private Collection $productsOrders;

    public function __construct()
    {
        $this->productsOrders = new ArrayCollection();
    }

    // --- Getters & Setters ---
    public function getId(): ?int { return $this->id; }
    public function getStatus(): ?string { return $this->status; }
    public function setStatus(string $status): static { $this->status = $status; return $this; }
    public function getClient(): ?Client { return $this->client; }
    public function setClient(?Client $client): static { $this->client = $client; return $this; }

    /**
     * @return Collection<int, ProductsOrders>
     */
    public function getProductsOrders(): Collection { return $this->productsOrders; }

    public function addProductsOrder(ProductsOrders $productsOrder): static
    {
        if (!$this->productsOrders->contains($productsOrder)) {
            $this->productsOrders->add($productsOrder);
            $productsOrder->setOrder($this);
        }
        return $this;
    }

    public function removeProductsOrder(ProductsOrders $productsOrder): static
    {
        if ($this->productsOrders->removeElement($productsOrder)) {
            if ($productsOrder->getOrder() === $this) {
                $productsOrder->setOrder(null);
            }
        }
        return $this;
    }
}
