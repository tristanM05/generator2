<?php

namespace App\Controller;

use App\Entity\Name;
use App\Form\NameType;
use App\Form\Name2Type;
use App\Entity\Categorie;
use App\Form\AddNameType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminNameController extends AbstractController
{
    /**
     * @Route("/admin/categ/{id}/name", name="admin_name")
     */
    public function index(CategorieRepository $repo, $id)
    {
        $name = $repo->findBy(['id' => $id]);
        return $this->render('admin/admin_name.html.twig', [
            'categorie' => $name
        ]);
    }


    /**
     * ajout name
     * @Route("/admin/categ/name/create/{id}", name="createName")
     */
    public function ajout(Request $req, EntityManagerInterface $manager,Categorie $categorie)
    {
        
        $form = $this->createForm(NameType::class, $categorie);

        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $names = $form->getViewData()->getNames();

            foreach ($names as $name) {
                $name->setIsActive(1);
                $name->setCategorie($categorie);
                $manager->persist($name);
            }
            $manager->flush();

                $this->addFlash("success", "l'ajout a bien été effectué ");
                return $this->redirectToRoute("admin_categ");
        }

        return $this->render('admin/Addname.html.twig', [
            // "actu" => $name,
            "form" => $form->createView(),
        ]);
    }

    /**
     * edit Name
     * @Route("*admin/categ/name/edit/{id}", name="NameEdit")
     */

    public function edit(Name $name, Request $req, EntityManagerInterface $manager)
    {

        $form = $this->createForm(AddNameType::class, $name);

        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($name);
            $manager->flush();

            $this->addFlash("success", "la modification a bien été effectué ");
            return $this->redirectToRoute("admin_categ");
        }

        return $this->render('admin/ModifName.html.twig', [
            "actu" => $name,
            "form" => $form->createView(),
        ]);
    }

    /**
     * delete
     * 
     * @Route("/admin/name/{id}", name="supName", methods="delete")
     */
    public function delete(Name $name, EntityManagerInterface $manager, Request $req)
    {
        if ($this->isCsrfTokenValid('SUP' . $name->getId(), $req->get('_token'))) {

            $manager->remove($name);
            $manager->flush();
            $this->addFlash("success", "La suppression a bien été éffectuée");
            return $this->redirectToRoute("admin_categ");
        }
    }
}
