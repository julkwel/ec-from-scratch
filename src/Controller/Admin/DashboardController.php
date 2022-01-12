<?php

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class DashboardController extends AbstractBaseAdminController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('admin/dashboard.html.twig');
    }
}
