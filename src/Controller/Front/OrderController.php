<?php

namespace App\Controller\Front;

use App\Entity\Taxon;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/order", name="order_")
 */
class OrderController extends AbstractBaseFrontController
{
    /**
     * @Route("/{taxon?}", name="home")
     *
     * @return Response
     */
    public function index(Request $request, ?Taxon $taxon = null)
    {
        $page = $request->query->getInt('page', 1);
        $queryBuilder = $this->productRepository->findProducts($request->get('search'));

        if ($taxon instanceof Taxon) {
            $queryBuilder = $this->productRepository->findProductByTaxon($taxon->getId());
        }

        $pagination = $this->paginator->paginate($queryBuilder, $page, 10);
        $taxons = $this->taxonRepository->findAll();

        return $this->render('front/order/order_home.html.twig', ['products' => $pagination, 'taxons' => $taxons]);
    }
}
