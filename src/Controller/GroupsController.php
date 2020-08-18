<?php

namespace App\Controller;

use App\Form\GroupsFormType;
use http\Client\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Groups;
use Symfony\Component\HttpFoundation\Request;

class GroupsController extends AbstractController
{
    /**
     * @Route("/groups", name="groups")
     */
    public function index()
    {
        return $this->render('groups/index.html.twig', [
            'controller_name' => 'GroupsController',
        ]);
    }
    /**
     * @Route("/add-groups", name="add_groups")
     */
    public function addGroup(Request $request)
    {
        $groups = new Groups();
        $form = $this->createForm(GroupsFormType::class, $groups);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($groups);
            $entityManager->flush();
        }

        return $this->render("groups/groups-form.html.twig", [
            "form_title" => "Ajouter un groups",
            "form_groups" => $form->createView(),
        ]);
    }
    /**
     * @Route("/groups/{id}", name="groups")
     */
    public function groups(int $id): \Symfony\Component\HttpFoundation\Response
    {
        $groups = $this->getDoctrine()->getRepository(groups::class)->find($id);

        return $this->render("groups/groups.html.twig", [
            "groups" => $groups,
        ]);
    }
}
