<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 */
class Product
{
    use SoftDeleteableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Taxon::class)
     */
    private $taxon;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $tags;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $discount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $priceTtc;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $stock;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPromo;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isNewness;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $promoDiscount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $unit;

    /**
     * @ORM\ManyToOne(targetEntity=Provider::class, inversedBy="products")
     */
    private $provider;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isEnabled;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isValid;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $images = [];

    public function __construct()
    {
        $this->isEnabled = false;
        $this->isValid = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaxon(): ?Taxon
    {
        return $this->taxon;
    }

    public function setTaxon(?Taxon $taxon): self
    {
        $this->taxon = $taxon;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getTagsToArray()
    {
        return explode(',', $this->tags);
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;
        $this->priceTtc = $price * 1.2;

        return $this;
    }

    public function getDiscount(): ?string
    {
        return $this->discount;
    }

    public function setDiscount(?string $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function priceTTcRemise(): string
    {
        $discountedPrice = (int) $this->priceTtc * (1 - ((int) $this->discount / 100));

        return number_format($discountedPrice, 2, ',', ' ');
    }

    public function getPriceTtc(): ?string
    {
        return $this->priceTtc;
    }

    public function setPriceTtc(?float $priceTtc): self
    {
        $this->priceTtc = $priceTtc;

        return $this;
    }

    public function getStock(): ?float
    {
        return $this->stock;
    }

    public function setStock(?float $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getIsPromo(): ?bool
    {
        return $this->isPromo;
    }

    public function setIsPromo(?bool $isPromo): self
    {
        $this->isPromo = $isPromo;

        return $this;
    }

    public function getIsNewness(): ?bool
    {
        return $this->isNewness;
    }

    public function setIsNewness(?bool $isNewness): self
    {
        $this->isNewness = $isNewness;

        return $this;
    }

    public function getPromoDiscount(): ?float
    {
        return $this->promoDiscount;
    }

    public function setPromoDiscount(?float $promoDiscount): self
    {
        $this->promoDiscount = $promoDiscount;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(?bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

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

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(?array $images): self
    {
        $this->images = $images;

        return $this;
    }
}
