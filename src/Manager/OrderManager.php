<?php

namespace App\Manager;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\User;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class OrderManager extends AbstractManager
{
    /**
     * @param OrderItem|null     $order
     * @param UserInterface|null $user
     * @param Request            $request
     *
     * @return OrderItem
     *
     * @throws Exception
     */
    public function generateOrderItem(?OrderItem $order, UserInterface $user, Request $request): OrderItem
    {
        $orderItem = $order ?? new OrderItem();
        $productCount = (int) $request->get('number') ?? 1;
        $product = $this->entityServices->getEntityManager()->getRepository(Product::class)->find($request->get('product'));

        if (!$product) {
            throw new Exception('Unable to find product');
        }

        if (!$orderItem->getId()) {
            $orderItem = $this->entityServices->getEntityManager()->getRepository(OrderItem::class)
                    ->findOneBy(['item' => $product, 'client' => $user, 'state' => OrderItem::PRE_CART]) ?? new OrderItem();
        }

        $orderItem->setCount($productCount);
        $orderItem->setItem($product);
        $orderItem->setClient($user);
        $orderItem->setTotal($product->getPriceTtc() * $productCount);
        $orderItem->setState(OrderItem::PRE_CART);

        $this->entityServices->save($orderItem);

        return $orderItem;
    }
}
