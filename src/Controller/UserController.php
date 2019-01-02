<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', ['users' => $userRepository->findAll()]);
    }

    /**
     * @Route("/new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", methods={"GET"}, requirements={"id" = "^[1-9]\d*$"})
     * @IsGranted("ROLE_USER")
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/{id}", methods={"GET"}, requirements={"id" = "^0$"})
     * @IsGranted("ROLE_USER")
     */
    public function showDefault() :Response
    {
        $this->addFlash('warning','L\'utilisateur demandé est un utilisateur virtuel, il remplace les utilisateurs supprimés.');

        return $this->redirectToRoute('app_build_index');
    }

    /**
     * @Route("/{id}/edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, User $user): Response
    {
        if ($user === $this->getUser() || $this->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(UserType::class, $user);
            $form->remove('password');
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('app_user_show', ['id' => $user->getId()]);
            }

            return $this->render('user/edit.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            ]);
        }

        $this->addFlash('danger', 'Vous n\'avez pas les droits.');

        return $this->redirectToRoute('app_default_index');
    }

    /**
     * @Route("/{id}/delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success','L\'utilisateur a bien été supprimé.');
        }

        return $this->redirectToRoute('app_user_index');
    }

    /**
     * @Route("/{id}/inactivate", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function inactivate(User $user)
    {
        if ($user === $this->getUser() || $this->isGranted('ROLE_ADMIN')) {
            $user->setIsActive(false);
            $this->getDoctrine()->getManager()->flush();
        }
        $this->addFlash('success','Le compte a bien été désactivé.');

        return $this->redirectToRoute('app_default_index');
    }

    /**
     * @Route("/{id}/activate", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function activate(User $user)
    {
        if ($user === $this->getUser() || $this->isGranted('ROLE_ADMIN')) {
            $user->setIsActive(true);
            $this->getDoctrine()->getManager()->flush();
        }
        $this->addFlash('success','Le compte a bien été activé.');

        if ($this->isGranted("ROLE_ADMIN")) return $this->redirectToRoute('app_user_index');

        return $this->redirectToRoute('app_default_index');
    }
}
