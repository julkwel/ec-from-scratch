<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findProducts(?string $query)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.label LIKE :val')
            ->setParameter('val', "%".$query."%")
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery();
    }

    public function findFeaturedProduct()
    {
        return $this->createQueryBuilder('p')
            ->where('p.isNewness = :val')
            ->setParameter('val', true)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()->getResult();
    }


    public function findPromoProduct()
    {
        return $this->createQueryBuilder('p')
            ->where('p.isPromo = :val')
            ->setParameter('val', true)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()->getResult();
    }
}
