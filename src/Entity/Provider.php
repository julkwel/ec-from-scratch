<?php

namespace App\Entity;

use App\Repository\ProviderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProviderRepository::class)
 */
class Provider
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $idProvider;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $productDiscount;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="provider")
     */
    private $products;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $nifStat;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contact;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="provider", cascade={"persist", "remove"})
     */
    private $user;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProvider(): ?string
    {
        return $this->idProvider;
    }

    public function setIdProvider(string $idProvider): self
    {
        $this->idProvider = $idProvider;

        return $this;
    }

    public function getProductDiscount(): ?int
    {
        return $this->productDiscount;
    }

    public function setProductDiscount(?int $productDiscount): self
    {
        $this->productDiscount = $productDiscount;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setProvider($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getProvider() === $this) {
                $product->setProvider(null);
            }
        }

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getNifStat(): ?string
    {
        return $this->nifStat;
    }

    public function setNifStat(?string $nifStat): self
    {
        $this->nifStat = $nifStat;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setProvider(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getProvider() !== $this) {
            $user->setProvider($this);
        }

        $this->user = $user;

        return $this;
    }
}
