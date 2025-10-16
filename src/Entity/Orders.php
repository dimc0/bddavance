<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?client $client_id = null;

    /**
     * @var Collection<int, ProductsOrders>
     */
    #[ORM\OneToMany(targetEntity: ProductsOrders::class, mappedBy: 'orders_id')]
    private Collection $productsOrders;

    public function __construct()
    {
        $this->productsOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getClientId(): ?client
    {
        return $this->client_id;
    }

    public function setClientId(?client $client_id): static
    {
        $this->client_id = $client_id;

        return $this;
    }

    /**
     * @return Collection<int, ProductsOrders>
     */
    public function getProductsOrders(): Collection
    {
        return $this->productsOrders;
    }

    public function addProductsOrder(ProductsOrders $productsOrder): static
    {
        if (!$this->productsOrders->contains($productsOrder)) {
            $this->productsOrders->add($productsOrder);
            $productsOrder->setOrdersId($this);
        }

        return $this;
    }

    public function removeProductsOrder(ProductsOrders $productsOrder): static
    {
        if ($this->productsOrders->removeElement($productsOrder)) {
            // set the owning side to null (unless already changed)
            if ($productsOrder->getOrdersId() === $this) {
                $productsOrder->setOrdersId(null);
            }
        }

        return $this;
    }
}
