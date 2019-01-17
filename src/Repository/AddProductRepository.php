<?php

namespace App\Repository;

use App\Entity\AddProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AddProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method AddProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method AddProduct[]    findAll()
 * @method AddProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AddProduct::class);
    }

    // /**
    //  * @return AddProduct[] Returns an array of AddProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AddProduct
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
