<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user")
     */
    public function index()
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users' => $user,
        ]);
    }

    /**
     * @Route("/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request): Response
    {
        $user = $this->getUser();
        $dataPoints = $this->getDoctrine()->getRepository(Tasks::class)->timeProjectUser($user->getId());

        for ($i = 0; $i < sizeof($dataPoints); $i++){
            $dataPoints[$i]['y'] = ceil($dataPoints[$i]['y']/60);
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('user/edit.html.twig', [
            'form_title' => "Edit account",
            'user' => $this->getUser(),
            'roles' => $user->getRoles(),
            'form_user' => $form->createView(),
            'dataPoints' => $dataPoints
        ]);
    }
}
