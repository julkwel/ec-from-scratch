<?php

namespace App\Controller\Middle;

use App\Entity\Product;
use App\Entity\Provider;
use App\Entity\Taxon;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Services\EntityServices;
use App\Services\FileUploader;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/provider/product", name="provider_product_")
 */
class ProviderProductController extends AbstractMiddleController
{
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var PaginatorInterface
     */
    private $paginator;
    /**
     * @var FileUploader
     */
    private $fileUploader;

    public function __construct(EntityServices $entityServices, ProductRepository $productRepository, PaginatorInterface $paginator, FileUploader $fileUploader)
    {
        parent::__construct($entityServices);
        $this->productRepository = $productRepository;
        $this->paginator = $paginator;
        $this->fileUploader = $fileUploader;
    }

    /**
     * @Route("/home/{taxon?}", name="list")
     */
    public function productList(Request $request, ?Taxon $taxon = null)
    {
        $page = $request->query->getInt('page', 1);
        $provider = $this->getUser()->getProvider();
        $queryBuilder = $this->productRepository->findProviderProducts($provider, $request->get('search'));
        if ($taxon instanceof Taxon) {
            $queryBuilder = $this->productRepository->findProviderProductsWithTaxon($provider, $taxon->getId(), $request->get('search'));
        }
        $pagination = $this->paginator->paginate($queryBuilder, $page, 10);

        return $this->render('middle/products/listing.html.twig', ['products' => $pagination]);
    }

    /**
     * @Route("/manage/{id?}", name="manage")
     */
    public function productManagement(Request $request, Product $product = null)
    {
        $product = $product ?? new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productImage = $form->get('image')->getData();
            $provider = $this->getUser()->getProvider();
            if (!$product->getIsValid()) {
                $product->setIsValid(false);
            }

            if ($productImage instanceof UploadedFile) {
                $this->fileUploader->setTargetDirectory($this->getParameter('product_image'));
                $filename = $this->fileUploader->upload($productImage);
                $product->setImage($filename);
                $product->setProvider($provider);
            }
            $this->entityServices->save($product);

            return $this->redirectToRoute('provider_product_list');
        }

        return $this->render('middle/products/manage_product.html.twig', ['product' => $product, 'form' => $form->createView()]);
    }

    /**
     * @Route("/state/{id}", name="change_state")
     */
    public function productChangeState(Product $product): RedirectResponse
    {
        $product->setIsEnabled(!$product->getIsEnabled());
        $this->entityServices->save($product);

        return $this->redirectToRoute('provider_product_list');
    }

}
