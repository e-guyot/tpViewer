<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Form\TasksType;
use App\Repository\TasksRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tasks")
 */
class TasksController extends AbstractController
{

    /**
     * @Route("/new", name="tasks_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $task = new Tasks();
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('project_show', ['id' => $task->getIdProject()->getId()]));
        }

        return $this->render('tasks/new.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="tasks_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tasks $task): Response
    {
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('task_show', ['id' => $task->getId()]));
        }

        return $this->render('tasks/edit.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="tasks_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tasks $task): Response
    {
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tasks');
    }

    /**
     * @Route("/show/{id}", name="task_show", methods={"GET","POST"})
     */
    public function showtask(TasksRepository $tasksRepository, Tasks $task): Response
    {
        $seconds = $task->getTimer();
        if (isset($seconds)) {
            $timer = gmdate("H:i:s", $seconds);
        }
        return $this->render('tasks/task.html.twig', [
            'seconds' => $seconds,
            'timer' => $timer,
            'task' => $tasksRepository->findTask($task->getId()),
        ]);
    }

    /**
     * @Route("/ajax/task/start", name="ajax_task_start", methods={"GET","POST"})
     */
    public function startTimer(Request $request): Response
    {
        $taskId = $request->get('taskId');
        $task = $this->getDoctrine()->getRepository(Tasks::class)->find($taskId);
        $date = $task->getDateStart();

        $startDate = new \DateTime();
        $startDate->format('Y-m-d\TH:i:s.u');

        if (!isset($date)) {
            $task->setDateStart($startDate);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
        }

        return $this->json("ok");
    }

    /**
     * @Route("/ajax/task/stop", name="ajax_task_stop", methods={"GET","POST"})
     */
    public function stopTimer(Request $request): Response
    {
        $taskId = $request->get('taskId');
        $task = $this->getDoctrine()->getRepository(Tasks::class)->find($taskId);

        $dateEnd = new \DateTime();
        $dateEnd->format('Y-m-d\TH:i:s.u');

        $time = $request->get('time');
        $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $time);
        sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
        $time_seconds = $hours * 3600 + $minutes * 60 + $seconds;

        $task->setTimer($time_seconds);
        $task->setDateEnd($dateEnd);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($task);
        $entityManager->flush();


        return $this->json("ok");
    }
}
