<?php

namespace App\Controller\Front;

use App\Repository\ProductRepository;
use App\Repository\TaxonRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="front_")
 */
class HomePageController extends AbstractBaseFrontController
{
    /**
     * @Route(name="home")
     *
     * @param ProductRepository $productRepository
     * @param TaxonRepository   $taxonRepository
     *
     * @return Response
     */
    public function index(ProductRepository $productRepository, TaxonRepository $taxonRepository): Response
    {
        $featured = $productRepository->findFeaturedProduct();
        $promos = $productRepository->findPromoProduct();
        $taxons = $taxonRepository->findAll();

        return $this->render('front/home_page.html.twig', ['featured' => $featured, 'promos' => $promos, 'taxons' => $taxons]);
    }
}
