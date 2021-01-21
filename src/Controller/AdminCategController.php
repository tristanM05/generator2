<?php

namespace App\Controller;

use App\Form\CategType;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\NameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCategController extends AbstractController
{
    /**
     * affichage de toutes les catégories
     * @Route("/admin/categ", name="admin_categ")
     */
    public function index(CategorieRepository $repo)
    {
        $categ = $repo->findBy([], ["id" => "DESC"]);
        return $this->render('admin/admin_categ.html.twig', [
            "categ" => $categ
        ]);
    }

    /**
     * ajout et modif categ
     * @Route("/admin/categ/create", name="createCateg")
     * @Route("/admin/categ/{id}", name="modifCateg", methods ="POST|GET")
     *
     */
    public function ajoutEtModif(Categorie $categ = null, Request $req, EntityManagerInterface $manager){

        if (!$categ){
            $categ = new Categorie();
            $categ->setIsActive(0);
        }

        $form = $this->createForm(CategType::class, $categ);

        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $modif = $categ->getId() !== null;
            $manager->persist($categ);
            $manager->flush();

            $this->addFlash("sucess", ($modif) ? "la modification a bien été effecteur" : "l'ajout a bien été effectué");
            return $this->redirectToRoute("admin_categ");

        }

        return $this->render('admin/AMcateg.html.twig',[
            "catg" => $categ,
            "form" => $form->createView(),
            "edit" => $categ->getId() !== null
        ]);
        
    }

    /**
     * delete
     * 
     * @Route("/admin/categ/{id}", name="supCateg", methods="delete")
     */
    public function delete(Categorie $categ, EntityManagerInterface $manager, Request $req, NameRepository $repo){
        if ($this->isCsrfTokenValid('SUP'.$categ->getId(), $req->get('_token'))){
            $names = $repo->findBy(["categorie" => $categ]);

            foreach ($names as $name){
                $manager->remove($name);
                $manager->flush();
            }

            $manager->remove($categ);
            $manager->flush();
            $this->addFlash("success", "La suppression a bien été éffectuée");
            return $this->json([
                'code' => 200,
                'message' => "statut modifié",
                'active' => '<i class="fas fa-check text-success fa-2x"></i>',
            ],200);
        }
    }

}
