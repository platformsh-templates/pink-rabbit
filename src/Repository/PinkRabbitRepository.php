<?php

namespace App\Repository;

use App\Entity\PinkRabbit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method PinkRabbit|null find($id, $lockMode = null, $lockVersion = null)
 * @method PinkRabbit|null findOneBy(array $criteria, array $orderBy = null)
 * @method PinkRabbit[]    findAll()
 * @method PinkRabbit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PinkRabbitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PinkRabbit::class);
    }

    public function findLatestQueryBuilder(int $maxResults): QueryBuilder
    {
        return $this->createQueryBuilder('big_foot_sighting')
            ->setMaxResults($maxResults)
            ->orderBy('big_foot_sighting.createdAt', 'DESC');
    }

    // /**
    //  * @return PinkRabbit[] Returns an array of PinkRabbit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PinkRabbit
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
