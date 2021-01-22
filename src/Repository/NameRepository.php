<?php

namespace App\Repository;

use App\Entity\Name;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Name|null find($id, $lockMode = null, $lockVersion = null)
 * @method Name|null findOneBy(array $criteria, array $orderBy = null)
 * @method Name[]    findAll()
 * @method Name[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Name::class);
    }

    // /**
    //  * @return Name[] Returns an array of Name objects
    //  */
    
    public function nameRand($categorie)
    {
        return $this->createQueryBuilder('n')
            ->select('n','c')
            ->join('n.categorie', 'c')
            ->where('n.isActive = :1')
            ->andWhere('n.categorie = :categorie')
            ->orderBy('rand')
            ->setParameter('1', 1)
            ->setParameter('categorie', $categorie)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Name
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
