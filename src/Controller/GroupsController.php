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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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

//            $user = $this->getUser();
//            $user->setRoles(['ROLE_ADMIN', 'ROLE_GROUP_' . $userGroup->getId()]);
//            $entityManager->persist($user);
//            $entityManager->flush();

            return $this->redirectToRoute('groups');
        }
        return $this->render("groups/new.html.twig", [
            "form_title" => "Add a group",
            "form_groups" => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="groups_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Groups $group): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $form = $this->createForm(GroupsFormType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('groups');
        }

        return $this->render('groups/edit.html.twig', [
            'form_title' => "Edit group",
            'group' => $group,
            'user_role' => $this->getUser()->getRoles(),
            'form_groups' => $form->createView(),
            'users' => $users,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="groups_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Groups $group): Response
    {
        $checkProjects = $this->getDoctrine()->getRepository(Groups::class)->checkGroupProjects($group->getId());

        if (!empty($checkProjects)) {
            $this->addFlash(
                'notice',
                'You can\'t delete that group.'
            );

            return $this->redirectToRoute('groups');
        } else {
            if ($this->isCsrfTokenValid('delete' . $group->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();

                $userGroups = $this->getDoctrine()->getRepository(UserGroup::class)->getGroup($group->getId());
                foreach ($userGroups as $userGroup) {
                    $entityManager->remove($userGroup);
                }
                $entityManager->remove($group);
                $entityManager->flush();
            }

            return $this->redirectToRoute('groups');
        }
    }

    /**
     * @Route("/add/{id}/{name}", name="add_user", methods={"GET","POST"})
     * @ParamConverter("group", options={"mapping": {"name" : "name"}})
     * @Template()
     */
    public function addUser(Request $request, User $user, Groups $group): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(GroupsFormType::class, $group);
        $form->handleRequest($request);

        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        $userGroup = new UserGroup();
        $userGroup->setIdUser($user);
        $userGroup->setIdGroup($group);

        $checkExists = $this->getDoctrine()->getRepository(UserGroup::class)->findBy(array('id_user' => $userGroup->getIdUser(), 'id_group' => $userGroup->getIdGroup()));

        if (empty($checkExists)) {
            $entityManager->persist($userGroup);
            $entityManager->flush();
        } else {
            $this->addFlash(
                'notice',
                'User already added.'
            );
        }

        return $this->redirect($this->generateUrl('groups_edit', ['id' => $group->getId()]));
    }

}
