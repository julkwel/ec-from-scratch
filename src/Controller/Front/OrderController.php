<?php

namespace App\Controller\Front;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/order", name="order_")
 */
class OrderController extends AbstractBaseFrontController
{
    /**
     * @Route("/", name="home")
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $page = $request->query->getInt('page', 1);
        $queryBuilder = $this->productRepository->findProducts($request->get('search'));
        $pagination = $this->paginator->paginate($queryBuilder, $page, 2);
        $taxons = $this->taxonRepository->findAll();

        return $this->render('front/order/order_home.html.twig', ['products' => $pagination, 'taxons' => $taxons]);
    }
}
