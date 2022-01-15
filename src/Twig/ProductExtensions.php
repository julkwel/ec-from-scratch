<?php

namespace App\Twig;

use App\Repository\ProductRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ProductExtensions extends AbstractExtension
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('count_product', [$this, 'countTaxonProduct']),
        ];
    }

    public function countTaxonProduct(?int $taxonId)
    {
        return $this->productRepository->countProductByRayon($taxonId);
    }
}
