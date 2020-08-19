<?php

namespace App\Controller;

use App\Form\GroupsFormType;
use http\Client\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Groups;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/groups")
 */
class GroupsController extends AbstractController
{
    /**
     * @Route("/", name="groups")
     */
    public function index()
    {
        return $this->render('groups/index.html.twig', [
            'controller_name' => 'GroupsController',
        ]);
    }
    /**
     * @Route("/new", name="groups_new")
     */
    public function newGroup(Request $request)
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

//    /**
//     * @Route("/edit/{id}", name="groups_edit", methods={"GET","POST"})
//     */
//    public function edit(Request $request, Groups $group)
//    {
//        $form = $this->createForm(Groups::class, $group);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
//
//            return $this->redirectToRoute('groups');
//        }
//
//        return $this->render('groups/form.html.twig', [
//            'form_title' => "Modifier groupe",
//            'group' => $group,
//            'form_groups' => $form->createView(),
//        ]);
//    }

}
