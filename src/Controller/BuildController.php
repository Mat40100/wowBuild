<?php

namespace App\Controller;

use App\Entity\Build;
use App\Entity\Comment;
use App\Form\BuildType;
use App\Form\CommentType;
use App\Repository\BuildRepository;
use App\Service\CheckRightService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/build")
 */
class BuildController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function index(BuildRepository $buildRepository): Response
    {
        return $this->render('build/index.html.twig', ['builds' => $buildRepository->findBy(['isActive'=>true])]);
    }

    /**
     * @Route("/new", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request): Response
    {
        $build = new Build();
        $form = $this->createForm(BuildType::class, $build);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $build->setAuthor($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($build);
            $entityManager->flush();

            return $this->redirectToRoute('app_build_index');
        }

        return $this->render('build/new.html.twig', [
            'build' => $build,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", methods={"GET","POST"})
     */
    public function show(Build $build, Request $request): Response
    {
        if (!$build->getIsActive()) {
            throw new NotFoundHttpException();
        }

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $this->isGranted("ROLE_USER")) {
            $comment->setAuthor($this->getUser());
            $comment->setBuild($build);

            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Votre commentaire a bien été posté!');

            return $this->redirect($request->getUri());
        }

        return $this->render('build/show.html.twig', [
            'build' => $build,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Build $build, CheckRightService $BuildService): Response
    {
        if (!$BuildService->isRightUser($this->getUser(), $build)) {
            $this->addFlash('warning','Vous n\'êtes pas le propriétaire de ce build.');

            return $this->redirectToRoute('app_build_index');
        }

        $form = $this->createForm(BuildType::class, $build);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'La modification a été prise en compte.');

            return $this->redirectToRoute('app_build_show', ['id' => $build->getId()]);
        }

        return $this->render('build/edit.html.twig', [
            'build' => $build,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, Build $build, CheckRightService $BuildService): Response
    {
        if (!$BuildService->isRightUser($this->getUser(), $build)) {
            $this->addFlash('warning','Vous n\'êtes pas le propriétaire de ce build.');

            return $this->redirectToRoute('app_build_index');
        }

        if ($this->isCsrfTokenValid('delete'.$build->getId(), $request->request->get('_token'))) {
            $build->setIsActive(false);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Votre build a été supprimé avec succes');
        }

        return $this->redirectToRoute('app_build_index');
    }
}
