<?php

namespace App\Controller\Admin\Products;

use App\Controller\Admin\AbstractBaseAdminController;
use App\Entity\Taxon;
use App\Form\TaxonType;
use App\Repository\TaxonRepository;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/taxon", name="admin_taxon_")
 */
class TaxonManagementController extends AbstractBaseAdminController
{
    /**
     * @Route("/", name="list")
     */
    public function home(TaxonRepository $taxonRepository)
    {
        return $this->render('admin/taxon/listing.html.twig', ['taxons' => $taxonRepository->findAllItems()]);
    }

    /**
     * @Route("/manage/{id?}", name="manage")
     *
     * @param Request    $request
     * @param Taxon|null $taxon
     *
     * @return Response
     *
     * @throws Exception
     */
    public function manageTaxon(Request $request, ?Taxon $taxon = null): Response
    {
        $taxon = $taxon ?? new Taxon();
        $form = $this->createForm(TaxonType::class, $taxon);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityServices->save($taxon);

            return $this->redirectToRoute('admin_taxon_list');
        }

        return $this->render('admin/taxon/manage_product.html.twig', ['taxon' => $taxon, 'form' => $form->createView()]);
    }

    /**
     * @Route("/delete/{id?}", name="delete")
     *
     * @param Taxon $taxon
     *
     * @return Response
     *
     * @throws Exception
     */
    public function removeTaxon(Taxon $taxon): Response
    {
        $this->entityServices->removeObject($taxon);

        return $this->redirectToRoute('admin_taxon_list');
    }
}
