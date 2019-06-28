<?php

namespace App\Repository;

use App\Entity\Birthday;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Birthday|null find($id, $lockMode = null, $lockVersion = null)
 * @method Birthday|null findOneBy(array $criteria, array $orderBy = null)
 * @method Birthday[]    findAll()
 * @method Birthday[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BirthdayRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Birthday::class);
    }

    /**
     * Get birthdays by date.
     *
     * @param \DateTime $date Birthday.
     *
     * @return array|null Birthdays.
     */
    public function findByBirthday(\DateTime $date): ?array
    {
        $day = $date->format('d');
        $month = $date->format('m');

        $result = $this->createQueryBuilder('b')
            ->andWhere('b.date like :birthday')
            ->setParameter('birthday', "%-{$month}-{$day} %")
            ->getQuery()
            ->getResult();

        return $result;
    }

    // /**
    //  * @return Birthday[] Returns an array of Birthday objects
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
    public function findOneBySomeField($value): ?Birthday
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
