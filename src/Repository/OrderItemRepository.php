<?php

namespace App\Repository;

use App\Entity\OrderItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderItem[]    findAll()
 * @method OrderItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderItem::class);
    }

    /**
     * @return Query Returns an array of OrderItem objects
     */
    public function findPrecartItemByUserState(int $userId, int $state): Query
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.state = :val')
            ->innerJoin('o.client', 'c', Join::WITH, 'c.id ='.$userId)
            ->setParameter('val', $state)
            ->orderBy('o.id', 'ASC')
            ->getQuery();
    }

    /**
     * @param $userId
     *
     * @return int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countPreCartItems($userId)
    {
        return $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->andWhere('o.state = :val')
            ->innerJoin('o.client', 'c', Join::WITH, 'c.id ='.$userId)
            ->setParameter('val', OrderItem::PRE_CART)
            ->orderBy('o.id', 'ASC')
            ->getQuery()->getSingleScalarResult();
    }
}
