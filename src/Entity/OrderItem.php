<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * @ORM\Entity(repositoryClass=OrderItemRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 */
class OrderItem
{
    use SoftDeleteableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     */
    private $item;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $client;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $total;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="items")
     */
    private $cart;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItem(): ?Product
    {
        return $this->item;
    }

    public function setItem(?Product $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(?float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getCart(): ?Order
    {
        return $this->cart;
    }

    public function setCart(?Order $cart): self
    {
        $this->cart = $cart;

        return $this;
    }
}
