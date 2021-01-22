<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Service\RandService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="homepage")
     */
    public function index(CategorieRepository $repo, RandService $rand)
    {
        $categ = $repo->findAll();
        $categ2 = $repo->findBy(['isActive' => 1]);
        $finalCategories = [];
        foreach($categ2 as $categRand){
            $finalCategories[] = [
                'category' => $categRand->getName(),
                'nameRand' => $rand->getRandomNameFromCategoryNames($categRand)
            ];
        }
        return $this->render('home.html.twig', [
            "categ" => $categ,
            "categ2" => $categ2,
            'finalCategories' => $finalCategories
        ]);
    }

    /**
     * 
     *@Route("/home/categ/{id}", name="setActive")
     * @param Categorie $categ
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function isActive(Categorie $categ, EntityManagerInterface $manager): Response {
        if($categ->getIsActive() == true){

            $categ->setIsActive(false);

            $manager->persist($categ);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => "statut modifié",
                'active' => '<i class="fas fa-check text-success fa-2x"></i>',
            ],200);
        }

        $categ->setIsActive(true);
        $manager->persist($categ);
        $manager->flush();

        return $this->json([
            'code' => 200,
            'message' => "statut modifié",
            'active' => '<i class="fas fa-check text-success fa-2x"></i>',
        ],200);
    }

    /**
     * Undocumented function
     *@Route("/home/tirage", name="tirage")
     * @param CategorieRepository $categRepo
     * @return void
     */
    public function tirage(){
        
        return $this->render('partials/tirageModal.html.twig', []);
    }
}
