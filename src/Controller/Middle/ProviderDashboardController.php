<?php

namespace App\Controller\Middle;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/provider/dashboard", name="provider_dashboard_")
 */
class ProviderDashboardController extends AbstractMiddleController
{
    /**
     * @Route("/home", name="home")
     *
     * @return Response
     */
    public function dashboardPage(): Response
    {
        return $this->render('middle/dashboard.html.twig');
    }
}
