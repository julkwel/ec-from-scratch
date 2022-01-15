<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    public const WAIT_FOR_VALIDATION = 1;
    public const IS_VALIDATED = 2;
    public const SHIPPEMENT_IN_PROCESS = 3;
    public const IS_DELIVERED = 5;
    public const IS_CANCELLED = 6;

    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $discount;

    /**
     * @ORM\OneToOne(targetEntity=Invoice::class, mappedBy="orders", cascade={"persist", "remove"})
     */
    private $invoice;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity=OrderItem::class, mappedBy="cart")
     */
    private $items;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $refShippement;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $refPaiement;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isValid;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $validatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $toShipped;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isShipped;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $shippedAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $orderRef;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $state;

    public function __construct()
    {
        $this->toShipped = false;
        $this->items = new ArrayCollection();
        $this->state = self::WAIT_FOR_VALIDATION;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(?int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): self
    {
        // unset the owning side of the relation if necessary
        if ($invoice === null && $this->invoice !== null) {
            $this->invoice->setOrders(null);
        }

        // set the owning side of the relation if necessary
        if ($invoice !== null && $invoice->getOrders() !== $this) {
            $invoice->setOrders($this);
        }

        $this->invoice = $invoice;

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

    /**
     * @return Collection|OrderItem[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setCart($this);
        }

        return $this;
    }

    public function removeItem(OrderItem $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getCart() === $this) {
                $item->setCart(null);
            }
        }

        return $this;
    }

    public function getRefShippement(): ?string
    {
        return $this->refShippement;
    }

    public function setRefShippement(?string $refShippement): self
    {
        $this->refShippement = $refShippement;

        return $this;
    }

    public function getRefPaiement(): ?string
    {
        return $this->refPaiement;
    }

    public function setRefPaiement(?string $refPaiement): self
    {
        $this->refPaiement = $refPaiement;

        return $this;
    }

    public function getIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(?bool $isValid): self
    {
        $this->isValid = $isValid;

        return $this;
    }

    public function getValidatedAt(): ?\DateTimeImmutable
    {
        return $this->validatedAt;
    }

    public function setValidatedAt(?\DateTimeImmutable $validatedAt): self
    {
        $this->validatedAt = $validatedAt;

        return $this;
    }

    public function getToShipped(): ?bool
    {
        return $this->toShipped;
    }

    public function setToShipped(bool $toShipped): self
    {
        $this->toShipped = $toShipped;

        return $this;
    }

    public function getIsShipped(): ?bool
    {
        return $this->isShipped;
    }

    public function setIsShipped(?bool $isShipped): self
    {
        $this->isShipped = $isShipped;

        return $this;
    }

    public function getShippedAt(): ?\DateTimeImmutable
    {
        return $this->shippedAt;
    }

    public function setShippedAt(?\DateTimeImmutable $shippedAt): self
    {
        $this->shippedAt = $shippedAt;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getOrderRef(): ?string
    {
        return $this->orderRef;
    }

    public function setOrderRef(?string $orderRef): self
    {
        $this->orderRef = $orderRef;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(?int $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getStateToString()
    {
        switch ($this->state):
            case self::IS_VALIDATED:
                return 'Achat validé';
            case  self::IS_DELIVERED:
                return 'Achat livré';
            case  self::SHIPPEMENT_IN_PROCESS:
                return 'Livraison en cours';
            case  self::IS_CANCELLED:
                return 'Achat Annulé';
            default:
                return 'En attente de validation';
        endswitch;
    }
}
