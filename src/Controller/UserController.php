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
     * @Route("/{id}", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', ['user' => $user]);
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
     * @Route("/{id}", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
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
        $this->addFlash('success','Le compte a bien été désactiver');

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
        $this->addFlash('success','Le compte a bien été activer');

        if ($this->isGranted("ROLE_ADMIN")) return $this->redirectToRoute('app_user_index');

        return $this->redirectToRoute('app_default_index');
    }
}
