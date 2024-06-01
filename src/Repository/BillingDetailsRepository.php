<?php

namespace App\Repository;

use App\Entity\BillingDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BillingDetails>
 *
 * @method BillingDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method BillingDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method BillingDetails[]    findAll()
 * @method BillingDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BillingDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BillingDetails::class);
    }

//    /**
//     * @return BillingDetails[] Returns an array of BillingDetails objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BillingDetails
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
