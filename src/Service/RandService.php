<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class RandService{
    private $manager;

    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }

    public function getRand(){
        return $this->manager->createQuery("SELECT n.title from App\Entity\Name n join n.categorie c where n.isActive = ?1 and c.id = n.categorie order by Rand()")
            ->setParameter(1, "1")
            ->setMaxResults(1)
            ->getResult();

        // return $this->manager->createQuery("SELECT n from App\Entity\Name n join n.categorie c where c.isActive = ?1 and n.isActive = ?2 and c.id = n.categorie order by Rand()")
        // ->setParameter(1, "1")
        // ->setParameter(2, "1")
        // ->setMaxResults($categRand)
        // ->getResult();

    }
}