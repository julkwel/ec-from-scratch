<?php

namespace App\Repository;

use App\Entity\Mailing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mailing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mailing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mailing[]    findAll()
 * @method Mailing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mailing::class);
    }

    /**
     * @return Query
     */
    public function findAllMailing()
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.id', 'DESC')
            ->getQuery();
    }


    /*
    public function findOneBySomeField($value): ?Mailing
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
