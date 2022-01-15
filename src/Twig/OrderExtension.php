<?php

namespace App\Twig;

use App\Repository\OrderItemRepository;
use App\Repository\OrderRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class OrderExtension extends AbstractExtension
{
    /**
     * @var OrderItemRepository
     */
    private $orderItemRepository;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    public function __construct(OrderItemRepository $orderItemRepository, OrderRepository $orderRepository)
    {
        $this->orderItemRepository = $orderItemRepository;
        $this->orderRepository = $orderRepository;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('count_user_pre_cart_item', [$this, 'countUserPrecartItem']),
            new TwigFunction('count_to_shipped_order', [$this, 'countToShippedOrder']),
            new TwigFunction('count_to_validate_order', [$this, 'countToValidateOrder']),
            new TwigFunction('count_to_finalize_order', [$this, 'countToFinalizeOrder']),
            new TwigFunction('count_shippement_process', [$this, 'countShippement']),
        ];
    }

    public function countUserPrecartItem(UserInterface $user)
    {
        return $this->orderItemRepository->countPreCartItems($user->getId());
    }

    public function countToShippedOrder()
    {
        return $this->orderRepository->countAllOrdersToShipped();
    }

    public function countToValidateOrder()
    {
        return $this->orderRepository->countAllToValidatedOrder();
    }

    public function countShippement()
    {
        return $this->orderRepository->countAllShippementInProcess();
    }

    public function countToFinalizeOrder()
    {
        return $this->orderRepository->countAlertValidatedOrder();
    }
}
