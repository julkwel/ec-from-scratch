<?php

namespace App\Controller\Front;

use App\Manager\ContactManager;
use Symfony\Component\HttpFoundation\Request;
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
     * @return Response
     */
    public function index(): Response
    {
        $featured = $this->productRepository->findBy(['isNewness' => true, 'isEnabled' => true, 'isValid' => true], [], 6);
        $promos = $this->productRepository->findBy(['isPromo' => true, 'isEnabled' => true, 'isValid' => true], [], 6);
        $taxons = $this->taxonRepository->findBy([], [], 3);

        return $this->render('front/home_page.html.twig', ['featured' => $featured, 'promos' => $promos, 'taxons' => $taxons]);
    }

    /**
     * @Route("taxon", name="taxon_list")
     */
    public function taxonPage()
    {
        return $this->render('front/product/taxon.page.html.twig', ['taxons' => $this->taxonRepository->findAll()]);
    }

    /**
     * @Route("newness", name="newness_list")
     */
    public function newnessPage(Request $request)
    {
        $page = $request->query->getInt('page', 1);
        $queryBuilder = $this->productRepository->findFeaturedProduct();
        $pagination = $this->paginator->paginate($queryBuilder, $page, 10);

        return $this->render('front/product/newness.page.html.twig', ['featured' => $pagination]);
    }

    /**
     *
     * @Route("contact", name="contact")
     *
     * @param Request        $request
     * @param ContactManager $contactManager
     *
     * @return Response
     */
    public function contactPage(Request $request, ContactManager $contactManager)
    {
        if ($request->isMethod('POST')) {
            $contactManager->sendMailFromClient($request);
            $this->addFlash('success', 'Merci d\'avoir nous contacter');

            return $this->redirectToRoute('front_home');
        }

        return $this->render('front/contact/contact.html.twig', ['' => '']);
    }
}
