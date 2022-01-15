<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @return Query Returns an array of Order objects
     */
    public function findUserOrder(User $user)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.client = :val')
            ->setParameter('val', $user)
            ->orderBy('o.id', 'ASC')
            ->getQuery();
    }

    public function findAllToValidatedOrder()
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.isValid = :val OR o.isValid IS NULL')
            ->setParameter('val', false)
            ->orderBy('o.id', 'ASC')
            ->getQuery();
    }

    public function countAllToValidatedOrder()
    {
        return $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->andWhere('o.isValid = :val OR o.isValid IS NULL')
            ->setParameter('val', false)
            ->orderBy('o.id', 'ASC')
            ->getQuery()->getSingleScalarResult();
    }

    public function findAllValidatedOrder()
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.isValid = :val')
            ->andWhere('(o.toShipped = :notship OR o.toShipped IS NULL) OR (o.toShipped = :ship AND o.state = :state)')
            ->setParameter('val', true)
            ->setParameter('notship', false)
            ->setParameter('ship', true)
            ->setParameter('state', Order::SHIPPEMENT_IN_PROCESS)->getQuery();
    }


    public function countAlertValidatedOrder()
    {
        return $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->andWhere('o.isValid = :val')
            ->andWhere('(o.toShipped = :notship OR o.toShipped IS NULL) OR (o.toShipped = :ship AND o.state = :state)')
            ->andWhere('o.isShipped = :ns OR (o.isShipped IS NULL)')
            ->setParameter('val', true)
            ->setParameter('notship', false)
            ->setParameter('ns', false)
            ->setParameter('ship', true)
            ->setParameter('state', Order::SHIPPEMENT_IN_PROCESS)->getQuery()->getSingleScalarResult();
    }

    public function findAllOrdersToShipped()
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.isValid = :val')
            ->andWhere('o.isShipped = :ship OR o.isShipped IS NULL')
            ->andWhere('o.toShipped = :toship')
            ->setParameter('val', true)
            ->setParameter('toship', true)
            ->setParameter('ship', false)
            ->orderBy('o.id', 'ASC')
            ->getQuery();
    }

    public function countAllOrdersToShipped()
    {
        return $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->andWhere('o.isValid = :val')
            ->andWhere('o.isShipped = :ship OR o.isShipped IS NULL')
            ->andWhere('o.toShipped = :toship')
            ->setParameter('val', true)
            ->setParameter('toship', true)
            ->setParameter('ship', false)
            ->orderBy('o.id', 'ASC')
            ->getQuery()->getSingleScalarResult();
    }

    public function countAllOrderFinalProcess()
    {
        return $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->andWhere('o.isValid = :val')
            ->andWhere('o.isShipped = :ship')
            ->setParameter('val', true)
            ->setParameter('ship', false)
            ->orderBy('o.id', 'ASC')
            ->getQuery()->getSingleScalarResult();
    }

    public function countAllShippementInProcess()
    {
        return $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->andWhere('o.state = :inship')
            ->setParameter('inship', Order::SHIPPEMENT_IN_PROCESS)
            ->getQuery()->getSingleScalarResult();
    }
}
