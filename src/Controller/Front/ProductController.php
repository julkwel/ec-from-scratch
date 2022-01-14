<?php

namespace App\Controller\Front;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product", name="product_")
 */
class ProductController extends AbstractBaseFrontController
{
    /**
     * @Route("/details/{id}", name="details")
     */
    public function details(Product $product): Response
    {
        return $this->render('front/product/details.html.twig', ['product' => $product]);
    }
}
