<?php

namespace App\Controller;

use App\Entity\Name;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NameController extends AbstractController
{
    /**
     * @Route("/user/categ/{id}/name", name="user_name")
     */
    public function index(CategorieRepository $repo, $id)
    {
        $name = $repo->findBy(['id' => $id]);
        return $this->render('user/user_name.html.twig', [
            'categorie' => $name
        ]);
    }

    
    
    /**
     * 
     *@Route("/home/categ/name/{id}", name="setActiveName")
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function isActiveName(Name $name, EntityManagerInterface $manager): Response {
        if($name->getIsActive() == true){

            $name->setIsActive(false);

            $manager->persist($name);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => "statut modifié",
                'active' => '<i class="fas fa-check text-success fa-2x"></i>',
            ],200);
        }

        $name->setIsActive(true);
        $manager->persist($name);
        $manager->flush();

        return $this->json([
            'code' => 200,
            'message' => "statut modifié",
            'active' => '<i class="fas fa-check text-success fa-2x"></i>',
        ],200);
    }
}
