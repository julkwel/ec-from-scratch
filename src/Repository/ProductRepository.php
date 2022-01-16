<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
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
            ->where('p.label LIKE :val')
            ->orderBy('p.id', 'DESC')
            ->setParameter('val', "%".$query."%")
            ->getQuery();
    }

    public function findProductByTaxon(?int $taxonId, ?string $query = '')
    {
        return $this->createQueryBuilder('p')
            ->where('p.label LIKE :val')
            ->setParameter('val', "%".$query."%")
            ->innerJoin('p.taxon', 't', Join::WITH, 't.id ='.$taxonId)
            ->orderBy('p.id', 'DESC')
            ->getQuery();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countProductByRayon(int $taxonId)
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->innerJoin('p.taxon', 't', Join::WITH, 't.id ='.$taxonId)
            ->getQuery()->getSingleScalarResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countProductWithoutRayon()
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.taxon IS NULL')
            ->getQuery()->getSingleScalarResult();
    }

    public function findProductWithoutRayon(): Query
    {
        return $this->createQueryBuilder('p')
            ->where('p.taxon IS NULL')
            ->getQuery();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countAllProducts()
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->getQuery()->getSingleScalarResult();
    }

    public function findFeaturedProduct()
    {
        return $this->createQueryBuilder('p')
            ->where('p.isNewness = :val')
            ->setParameter('val', true)
            ->orderBy('p.id', 'DESC')
            ->getQuery();
    }

    public function findPromoProduct()
    {
        return $this->createQueryBuilder('p')
            ->where('p.isPromo = :val')
            ->setParameter('val', true)
            ->orderBy('p.id', 'DESC')
            ->getQuery();
    }
}
