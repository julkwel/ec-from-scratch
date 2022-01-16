<?php

namespace App\Controller\Admin\Products;

use App\Controller\Admin\AbstractBaseAdminController;
use App\Entity\Product;
use App\Entity\Taxon;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/product", name="admin_product_")
 */
class ProductManagementController extends AbstractBaseAdminController
{
    /**
     * @Route("/rayon/{taxon?}", name="list")
     */
    public function home(Request $request, ProductRepository $productRepository, Taxon $taxon = null): Response
    {
        $page = $request->query->getInt('page', 1);
        $queryBuilder = $productRepository->findProducts($request->get('search'));
        if ($taxon instanceof Taxon) {
            $queryBuilder = $productRepository->findProductByTaxon($taxon->getId(), $request->get('search'));
        }
        $pagination = $this->pagination->paginate($queryBuilder, $page, 10);

        return $this->render('admin/product/listing.html.twig', ['products' => $pagination]);
    }

    /**
     * @Route("/manage/{id?}", name="manage")
     *
     * @param Request      $request
     * @param Product|null $product
     *
     * @return Response
     * @throws Exception
     */
    public function manageProduct(Request $request, ?Product $product = null): Response
    {
        $product = $product ?? new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productImage = $form->get('image')->getData();
            if ($productImage instanceof UploadedFile) {
                $this->fileUploader->setTargetDirectory($this->getParameter('product_image'));
                $filename = $this->fileUploader->upload($productImage);
                $product->setImage($filename);
            }

            $this->entityServices->save($product);

            return $this->redirectToRoute('admin_product_list');
        }

        return $this->render('admin/product/manage_product.html.twig', ['product' => $product, 'form' => $form->createView()]);
    }

    /**
     * @Route("/remove/{id?}", name="remove")
     */
    public function removeProduct(Product $product)
    {
        $this->entityServices->getEntityManager()->remove($product);
        $this->entityServices->getEntityManager()->flush();

        return $this->redirectToRoute('admin_product_list');
    }
}
