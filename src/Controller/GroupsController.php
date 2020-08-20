<?php

namespace App\Controller;

use App\Entity\UserGroup;
use App\Entity\User;
use App\Form\GroupsFormType;
use App\Repository\GroupsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
    public function index(GroupsRepository $groupsRepository): Response
    {
        return $this->render('groups/index.html.twig', [
            'groups' => $groupsRepository->findUserGroup($this->getUser()->getId()),
            'controller_name' => 'GroupsController',
        ]);
    }

    /**
     * @Route("/new", name="groups_new")
     */
    public function newGroup(Request $request): Response
    {
        $groups = new Groups();
        $userGroup = new UserGroup();
        $userGroup->setIdUser($this->getUser());

        $form = $this->createForm(GroupsFormType::class, $groups);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($groups);
            $entityManager->flush();

            $userGroup->setIdGroup($groups);
            $entityManager->persist($userGroup);
            $entityManager->flush();
        }

        return $this->render("groups/form.html.twig", [
            "form_title" => "Ajouter un groups",
            "form_groups" => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="groups_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Groups $group): Response
    {
        $form = $this->createForm(GroupsFormType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('groups');
        }

        return $this->render('groups/form.html.twig', [
            'form_title' => "Modifier groupe",
            'group' => $group,
            'form_groups' => $form->createView(),
        ]);
    }


    //TODO: delete all user_group + ne peut pas être delete si utilisé dans un projet
//    /**
//     * @Route("/delete/{id}", name="groups_delete", methods={"DELETE"})
//     */
//    public function delete(Request $request, Groups $group): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$group->getId(), $request->request->get('_token'))) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->remove($group);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('groups');
//    }

}
