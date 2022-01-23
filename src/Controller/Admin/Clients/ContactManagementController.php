<?php

namespace App\Controller\Admin\Clients;

use App\Controller\Admin\AbstractBaseAdminController;
use App\Entity\Mailing;
use App\Repository\MailingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/contact", name="admin_contact_")
 */
class ContactManagementController extends AbstractBaseAdminController
{
    /**
     * @Route("/list", name="listing")
     */
    public function listing(Request $request, MailingRepository $mailingRepository)
    {
        $page = $request->query->getInt('page', 1);
        $queryBuilder = $mailingRepository->findAllMailing();
        $pagination = $this->pagination->paginate($queryBuilder, $page, 5);

        return $this->render('admin/contact/mailing_list.html.twig', ['mailings' => $pagination]);
    }

    /**
     * @Route("/details/{id}", name="details")
     *
     * @param Mailing $mailing
     *
     * @return Response
     */
    public function details(Mailing $mailing)
    {
        return $this->render('admin/contact/mailing_details.html.twig', ['mail' => $mailing]);
    }

    /**
     * @Route("remove/{id}", name="remove")
     */
    public function removeMail(Mailing $mailing)
    {
        $this->entityServices->getEntityManager()->remove($mailing);
        $this->entityServices->save($mailing);

        return $this->redirectToRoute('admin_contact_listing');
    }
}
