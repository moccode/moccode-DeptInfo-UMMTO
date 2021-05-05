<?php

namespace App\Controller;

use App\Entity\ClasseDeCours;
use App\Form\ClasseDeCoursType;
use App\Repository\ClasseDeCoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;


class ClasseDeCoursController extends AbstractController
{
    /**
     * @Route("/classes", name="app_classedecours_index", methods={"GET"})
     */
    public function index(ClasseDeCoursRepository $classeDeCoursRepository): Response
    {
        $classesDeCours = $classeDeCoursRepository->findAll();

        return $this->render('classe_de_cours/index.html.twig', compact("classesDeCours"));
    }

    /**
     * @Route("/classes/creer", name="app_classedecours_creer", methods={"GET","POST"})
     */
    public function creer(Request $request, EntityManagerInterface $em): Response
    {
        $classeDeCours = new ClasseDeCours();
        $form = $this->createForm(ClasseDeCoursType::class, $classeDeCours);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($classeDeCours);
            $em->flush();

            return $this->redirectToRoute("app_classedecours_index");
        }

        return $this->render('classe_de_cours/creer.html.twig', [
            "formClasseDeCours" => $form->createView()
        ]);
    }

    /**
     * @Route("/classes/{id_classedecours<[0-9]+>}", name="app_classedecours_consulter", methods={"GET"})
     * @Entity("classeDeCours", expr="repository.find(id_classedecours)")
     * 
     */
    public function consulter(ClasseDeCours $classeDeCours): Response
    {
        return $this->render('classe_de_cours/consulter.html.twig', compact("classeDeCours"));
    }


    /**
     * @Route("/classes/{id_classedecours<[0-9]+>}/editer", name="app_classedecours_editer", methods={"GET","PUT"})
     * @Entity("classeDeCours", expr="repository.find(id_classedecours)")
     * 
     */
    public function editer(ClasseDeCours $classeDeCours, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ClasseDeCoursType::class, $classeDeCours, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($classeDeCours);
            $em->flush();

            return $this->redirectToRoute("app_classedecours_index");
        }

        return $this->render('classe_de_cours/editer.html.twig', [
            "classeDeCours" => $classeDeCours,
            "formClasseDeCours" => $form->createView()
        ]);
    }

    /**
     * @Route("/classes/{id_classedecours<[0-9]+>}/supprimer", name="app_classedecours_supprimer", methods={"DELETE"})
     * @Entity("classeDeCours", expr="repository.find(id_classedecours)")
     */
    public function supprimer(ClasseDeCours $classeDeCours, EntityManagerInterface $em): Response
    {
        $em->remove($classeDeCours);
        $em->flush();
        return $this->redirectToRoute('app_classedecours_index');
    }
}
