<?php

namespace App\Controller\Admin\Clients;

use App\Controller\Admin\AbstractBaseAdminController;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/client", name="admin_client_")
 */
class ClientManagementController extends AbstractBaseAdminController
{
    /**
     * @Route("/list", name="list")
     *
     * @return Response
     */
    public function listing(Request $request, UserRepository $userRepository)
    {
        $page = $request->query->getInt('page', 1);
        $queryBuilder = $userRepository->findAllClients();
        $pagination = $this->pagination->paginate($queryBuilder, $page, 5);

        return $this->render('admin/clients/client_list.html.twig', ['clients' => $pagination]);
    }

    /**
     * @Route("/disable/{id}", name="disable")
     */
    public function disableClients(User $user)
    {
        $user->setIsEnabled(!$user->getIsEnabled());
        $this->entityServices->save($user);

        return $this->redirectToRoute('admin_client_list');
    }
}
