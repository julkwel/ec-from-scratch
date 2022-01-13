<?php

namespace App\Controller\Admin\Products;

use App\Controller\Admin\AbstractBaseAdminController;
use App\Entity\Product;
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
     * @Route("/", name="list")
     */
    public function home(Request $request, ProductRepository $productRepository): Response
    {
        return $this->render('admin/product/listing.html.twig', ['products' => $productRepository->findProducts($request->get('search'))]);
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
}
