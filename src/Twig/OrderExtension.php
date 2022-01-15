<?php

namespace App\Twig;

use App\Repository\OrderItemRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class OrderExtension extends AbstractExtension
{
    /**
     * @var OrderItemRepository
     */
    private $orderItemRepository;

    public function __construct(OrderItemRepository $orderItemRepository)
    {
        $this->orderItemRepository = $orderItemRepository;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('count_user_pre_cart_item' , [$this, 'countUserPrecartItem'])
        ];
    }

    public function countUserPrecartItem(UserInterface $user)
    {
        return $this->orderItemRepository->countPreCartItems($user->getId());
    }
}
