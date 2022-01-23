<?php

namespace App\Manager;

use App\Entity\Adress;
use App\Entity\Contact;
use App\Entity\Invoice;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\OrderItemRepository;
use App\Services\EntityServices;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class OrderManager extends AbstractManager
{
    /**
     * @var OrderItemRepository
     */
    private $orderItemRepository;

    public function __construct(OrderItemRepository $orderItemRepository, EntityServices $entityServices)
    {
        parent::__construct($entityServices);
        $this->orderItemRepository = $orderItemRepository;
    }

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
            $orderItem = $this->entityServices->getEntityManager()
                    ->getRepository(OrderItem::class)
                    ->findOneBy(['item' => $product, 'client' => $user, 'state' => OrderItem::PRE_CART,]) ?? new OrderItem();
        }

        $orderItem->setCount($productCount);
        $orderItem->setItem($product);
        $orderItem->setClient($user);
        $orderItem->setTotal($product->getPriceTtc() * $productCount);
        $orderItem->setState(OrderItem::PRE_CART);
        $orderItem->getItem()->setStock($orderItem->getItem()->getStock() - $productCount);
        $this->entityServices->save($orderItem);

        return $orderItem;
    }

    /**
     * @param Request       $request
     *
     * @param UserInterface $user
     *
     * @return bool|Order
     */
    public function checkoutOrder(Request $request, UserInterface $user)
    {
        try {
            $order = new Order();
            $queryBuilder = $this->orderItemRepository->findPrecartItemByUserState($user->getId(), OrderItem::PRE_CART);
            $items = $queryBuilder->getResult();

            if (!$request->get('ref_paiement')) {
                return 'La reference de paiement est obligatoire';
            }

            $firstname = $request->get('firstname');
            $lastname = $request->get('lastname');
            $user->setFirstname($firstname);
            $user->setLastname($lastname);

            $hasAddress = $request->get('address') && $request->get('city');
            if ($request->get('address') && $request->get('city')) {
                $lot = $request->get('address');
                $ville = $request->get('city');
                $address = new Adress();
                $address->setLot($lot)->setVille($ville);
                $this->entityServices->save($address);

                $user->addAdress($address);
                $order->setAddresse($address);
            }

            if ($request->get('phone')) {
                $contact = $user->getContact() ?? new Contact();
                $contact->setPhone($request->get('phone'));
                $contact->setEmail($request->get('email'));
                $user->setContact($contact);

                $this->entityServices->getEntityManager()->persist($contact);
            }

            if ($request->get('to_shipped') && !$hasAddress) {
                throw new Exception("Pour votre livraison l'adresse est obligatoire");
            }

            $order->setToShipped($request->get('to_shipped') ?? false);
            $order->setClient($user);
            $order->setRefPaiement($request->get('ref_paiement'));
            $order->setNote($request->get('note'));

            /** @var OrderItem $item */
            foreach ($items as $item) {
                $item->setState(OrderItem::CART);
                $order->addItem($item);
            }
            $order->setOrderRef($this->generateOrderRef());
            $this->entityServices->save($order);
            $this->generateInvoice($order);

            return $order;
        } catch (Exception $exception) {
            dd($exception->getMessage());

            return false;
        }
    }

    public function generateInvoice(Order $order)
    {
        $invoice = new Invoice();
        $invoice->setNumber('VKT_IN'.$this->getNowToRef());
        $invoice->setOrders($order);
        $order->setInvoice($invoice);

        $this->entityServices->save($invoice);
    }

    /**
     * @throws Exception
     */
    public function generateOrderRef()
    {
        return 'VKT_OR'.$this->getNowToRef();
    }

    public function getNowToRef()
    {
        return (new DateTime('now'))->format('YmdHs');
    }
}
