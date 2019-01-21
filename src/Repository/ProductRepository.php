<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
    * @return Product[] Returns an array of Product objects
    */
    public function findOneEmptyLink()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.cheersLink is NULL')
            ->orWhere('p.cheersLink = :link')
            ->setParameter('link', "")
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }


    /**
     * @return Product[] Returns an array of Product objects
     */
    public function findOneEmptyImage()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.image IS NULL')
            ->orWhere('p.image = :image')
            ->orWhere('p.description = :image')
            ->orWhere('p.description IS NULL')
            ->setParameter('image', "")
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function findFirstNProducts($idStart, $nextN = 3)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id > :id')
            ->andWhere('p.infoComplete = :status')
            ->orderBy('p.id')
            ->setParameter(':id', $idStart)
            ->setParameter(':status', 1)
            ->setMaxResults($nextN)
            ->getQuery()
            ->getResult()
            ;
    }
    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
