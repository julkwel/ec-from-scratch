<?php

namespace App\Controller\Admin\Order;

use App\Controller\Admin\AbstractBaseAdminController;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/order", name="admin_order_")
 *
 * @IsGranted("ROLE_ADMIN")
 */
class OrderManagementController extends AbstractBaseAdminController
{
    /**
     * @Route("/list", name="to_validate_list")
     */
    public function listOrder(Request $request, OrderRepository $orderRepository)
    {
        $page = $request->query->getInt('page', 1);
        $queryBuilder = $orderRepository->findAllToValidatedOrder();
        $pagination = $this->pagination->paginate($queryBuilder, $page, 5);

        return $this->render('admin/order/order_list.html.twig', ['orders' => $pagination]);
    }

    /**
     * @Route("/validated", name="validate_list")
     */
    public function listOfValidOrder(Request $request, OrderRepository $orderRepository)
    {
        $page = $request->query->getInt('page', 1);
        $queryBuilder = $orderRepository->findAllValidatedOrder();
        $pagination = $this->pagination->paginate($queryBuilder, $page, 5);

        return $this->render('admin/order/order_success.html.twig', ['orders' => $pagination]);
    }


    /**
     * @Route("/to_shipped", name="to_shipped")
     */
    public function listOfToShipOrder(Request $request, OrderRepository $orderRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $queryBuilder = $orderRepository->findAllOrdersToShipped();
        $pagination = $this->pagination->paginate($queryBuilder, $page, 5);

        return $this->render('admin/order/order_to_shipped_list.html.twig', ['orders' => $pagination]);
    }

    /**
     * @Route("/remove/{id}", name="remove")
     *
     * @param Order $order
     *
     * @return RedirectResponse
     */
    public function removeOrder(Order $order)
    {
        try {
            $this->entityServices->getEntityManager()->remove($order);
            $this->entityServices->getEntityManager()->flush();

            return $this->redirectToRoute('admin_order_to_validate_list');
        } catch (Exception $exception) {
            $this->addFlash('error', 'Une erreur est survenue');

            return $this->redirectToRoute('admin_order_to_validate_list');
        }
    }

    /**
     * @Route("/validate/{id}", name="validate")
     *
     * @param Order $order
     *
     * @return RedirectResponse
     */
    public function validateCheckout(Order $order)
    {
        try {
            $order->setIsValid(true);
            $order->setState(Order::IS_VALIDATED);
            $this->entityServices->getEntityManager()->flush();

            if ($order->getToShipped()) {
                return $this->redirectToRoute('admin_order_to_shipped');
            }

            return $this->redirectToRoute('admin_order_validate_list');
        } catch (Exception $exception) {
            return $this->redirectToRoute('admin_order_to_validate_list');
        }
    }


    /**
     * @Route("/shippement/{id}", name="ship")
     *
     * @param Order $order
     *
     * @return RedirectResponse
     */
    public function shippement(Order $order)
    {
        try {
            $order->setState(Order::SHIPPEMENT_IN_PROCESS);
            $this->entityServices->getEntityManager()->flush();

            return $this->redirectToRoute('admin_order_validate_list');
        } catch (Exception $exception) {
            return $this->redirectToRoute('admin_order_to_shipped');
        }
    }

    /**
     * @Route("/shipped/{id}", name="shipped")
     *
     * @param Order $order
     *
     * @return RedirectResponse
     */
    public function shipped(Order $order)
    {
        try {
            $order->setState(Order::IS_DELIVERED);
            $order->setIsShipped(true);
            $this->entityServices->getEntityManager()->flush();

            return $this->redirectToRoute('admin_order_validate_list');
        } catch (Exception $exception) {
            return $this->redirectToRoute('admin_order_to_shipped');
        }
    }

    /**
     * @Route("/details/{id}", name="details")
     *
     * @param Order $order
     *
     * @return Response
     */
    public function detailsOrder(Order $order)
    {
        return $this->render('admin/order/order_details.html.twig', ['order' => $order]);
    }
}
