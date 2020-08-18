<?php

namespace App\Controller;

use App\Entity\Groups;
use App\Entity\User;
use App\Form\ProjectsFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Projects;
use Symfony\Component\HttpFoundation\Request;

class ProjectsController extends AbstractController
{
    /**
     * @Route("/projects", name="projects")
     */
    public function index()
    {
        return $this->render('projects/index.html.twig', [
            'controller_name' => 'ProjectsController',
        ]);
    }
    /**
     * @Route("/add-projects", name="add_projects")
     */
    public function addProjects ( Request $request ) : \Symfony\Component\HttpFoundation\Response
    {
        $projects = new Projects();
        $form = $this->createForm(ProjectsFormType::class, $projects);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($projects);
            $entityManager->flush();
        }

        return $this->render("projects/projects-form.html.twig", [
            "form_title" => "Ajouter un projects",
            "form_projects" => $form->createView(),
        ]);
    }
    /**
     * @Route("/projects", name="projects")
     */
    public function projects()
    {
        $user = $this->getUser();
        $projects = $this->getDoctrine()->getRepository(Projects::class)->findUserProject($user->getId());

        return $this->render('projects/projects.html.twig', [
            "projects" => $projects,
        ]);
    }

}
