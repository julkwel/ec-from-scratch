<?php

namespace App\Controller\Front;

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
    public function index()
    {
        return $this->render('front/home_page.html.twig');
    }
}
