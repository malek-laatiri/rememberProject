<?php

namespace App\front\Repository;

use App\common\Entity\Remainder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Remainder|null find($id, $lockMode = null, $lockVersion = null)
 * @method Remainder|null findOneBy(array $criteria, array $orderBy = null)
 * @method Remainder[]    findAll()
 * @method Remainder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RemainderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Remainder::class);
    }

    // /**
    //  * @return Remainder[] Returns an array of Remainder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Remainder
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
