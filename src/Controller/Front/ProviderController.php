<?php

namespace App\Controller\Front;

use App\Entity\Provider;
use App\Entity\User;
use App\Form\UserType;
use App\Manager\UserManager;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/fournisseur", name="provider_")
 */
class ProviderController extends AbstractBaseFrontController
{

    /**
     * @Route("/compte/{id?}", name="manage")
     *
     * @throws Exception
     */
    public function manageProvider(Request $request, UserManager $userManager, Provider $provider = null)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->generateProvider($request, $form, $user, $provider);
            $this->addFlash('success', 'Création compte fournisseur avec success !');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('front/provider/provider_management.html.twig', ['form' => $form->createView(), 'provider' => true]);
    }
}
