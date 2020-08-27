<?php

namespace App\Controller;

use App\Entity\Groups;
use App\Form\ProjectsFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Projects;
use App\Repository\TasksRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/projects")
 */
class ProjectsController extends AbstractController
{
    /**
     * @Route("/", name="projects")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $projects = $this->getDoctrine()->getRepository(Projects::class)->findUserProject($user->getId());

        return $this->render('projects/index.html.twig', [
            "projects" => $projects,
        ]);
    }

    /**
     * @Route("/new", name="projects_new")
     */
    public function newProject(Request $request): Response
    {
        $projects = new Projects();
        $user = $this->getUser();
        $form = $this->createForm(ProjectsFormType::class, $projects, ['user' => $user]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($projects);
            $entityManager->flush();

            return $this->redirectToRoute('projects');
        }

        return $this->render("projects/form.html.twig", [
            "form_title" => "Create a new project",
            "form_projects" => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="projects_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Projects $project): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProjectsFormType::class, $project, ['user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('projects');
        }

        return $this->render('projects/form.html.twig', [
            'form_title' => "Edit a project",
            'project' => $project,
            'form_projects' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="projects_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Projects $project): Response
    {
        if ($this->isCsrfTokenValid('delete' . $project->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($project);
            $entityManager->flush();
        }

        return $this->redirectToRoute('projects');
    }

    /**
     * @Route("/show/{id}", name="project_show")
     */
    public function show(TasksRepository $tasksRepository, Projects $project): Response
    {
        return $this->render('tasks/index.html.twig', [
            "tasks" => $tasksRepository->findTasks($project->getId()),
            "project_id" => $project->getId()
        ]);
    }

}
