<?php

namespace App\Controller\Front;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Taxon;
use App\Manager\OrderManager;
use App\Repository\OrderItemRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\TaxonRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/order", name="order_")
 */
class OrderController extends AbstractBaseFrontController
{
    /**
     * @var OrderManager
     */
    private $orderManager;

    public function __construct(ProductRepository $productRepository, TaxonRepository $taxonRepository, UserRepository $userRepository, OrderRepository $orderRepository, PaginatorInterface $paginator, ManagerRegistry $managerRegistry, OrderManager $orderManager)
    {
        parent::__construct($productRepository, $taxonRepository, $userRepository, $orderRepository, $paginator, $managerRegistry);
        $this->orderManager = $orderManager;
    }

    /**
     * @Route("/taxon/{taxon?}", name="home")
     *
     * @return Response
     */
    public function index(Request $request, ?Taxon $taxon = null)
    {
        $page = $request->query->getInt('page', 1);
        $queryBuilder = $this->productRepository->findProducts($request->get('search'));

        if ($taxon instanceof Taxon) {
            $queryBuilder = $this->productRepository->findProductByTaxon($taxon->getId());
        }

        $pagination = $this->paginator->paginate($queryBuilder, $page, 10);
        $taxons = $this->taxonRepository->findAll();

        return $this->render('front/order/order_home.html.twig', ['products' => $pagination, 'taxons' => $taxons]);
    }

    /**
     * @Route("/precart", name="cart")
     *
     * @throws Exception
     *
     * @IsGranted("ROLE_CLIENT")
     */
    public function precartPage(Request $request, OrderItemRepository $orderItemRepository)
    {
        $page = $request->query->getInt('page', 1);
        $queryBuilder = $orderItemRepository->findPrecartItemByUserState($this->getUser()->getId(), OrderItem::PRE_CART);
        $pagination = $this->paginator->paginate($queryBuilder, $page, 5);

        return $this->render('front/order/cart_page.html.twig', ['orderItems' => $pagination]);
    }

    /**
     * @Route("/cart_item/{id?}", name="create_item")
     *
     * @throws Exception
     *
     * @IsGranted("ROLE_CLIENT")
     */
    public function addToCart(Request $request, ?OrderItem $item = null): RedirectResponse
    {
        $this->orderManager->generateOrderItem($item, $this->getUser(), $request);

        return $this->redirectToRoute('order_cart');
    }

    /**
     * @Route("/remove_item/{id}", name="remove_item")
     *
     * @param OrderItem $orderItem
     *
     * @return RedirectResponse
     *
     * @IsGranted("ROLE_CLIENT")
     */
    public function removeOrderItem(OrderItem $orderItem): RedirectResponse
    {
        $this->managerRegistry->getManager()->remove($orderItem);
        $this->managerRegistry->getManager()->flush();

        return $this->redirectToRoute('order_cart');
    }

    /**
     * @Route("/checkout", name="checkout")
     *
     * @IsGranted("ROLE_CLIENT")
     */
    public function checkoutPage(Request $request, OrderItemRepository $orderItemRepository)
    {
        $page = $request->query->getInt('page', 1);
        $queryBuilder = $orderItemRepository->findPrecartItemByUserState($this->getUser()->getId(), OrderItem::PRE_CART);
        $pagination = $this->paginator->paginate($queryBuilder, $page, 20);

        if (0 === $pagination->getTotalItemCount()) {
            $this->addFlash('warning', 'Vous n\'avez aucun produits a acheter pour le moment !');

            return $this->redirectToRoute('order_cart');
        }

        if ($request->isMethod('POST')) {
            $order = $this->orderManager->checkoutOrder($request, $this->getUser());
            if ($order instanceof Order) {
                return $this->render('front/order/thanks.html.twig', ['order' => $order]);
            }
            $this->addFlash('error', 'Veuillez verifier la reference de votre paiement');

            return $this->redirectToRoute('order_checkout');
        }

        return $this->render('front/order/checkout.html.twig', ['items' => $pagination]);
    }

    /**
     * @Route("/tracking", name="tracking")
     *
     * @return Response
     *
     * @IsGranted("ROLE_CLIENT")
     */
    public function orderTracking(Request $request)
    {
        $page = $request->query->getInt('page', 1);
        $queryBuilder = $this->orderRepository->findUserOrder($this->getUser());
        $pagination = $this->paginator->paginate($queryBuilder, $page, 20);

        return $this->render('front/order/tracking.html.twig', ['orders' => $pagination]);
    }

    /**
     * @Route("/details/{id}", name="details")
     *
     * @param Order $order
     *
     * @return Response
     *
     * @IsGranted("ROLE_CLIENT")
     */
    public function orderDetails(Order $order)
    {
        if ($this->getUser()->getId() !== $order->getClient()->getId()) {
            return $this->redirectToRoute('order_home');
        }

        return $this->render('front/order/order_details.html.twig', ['items' => $order]);
    }
}
