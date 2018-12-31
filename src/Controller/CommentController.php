<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Service\CheckRightService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment/{id}/edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Comment $comment, CheckRightService $rightService): Response
    {
        if (!$rightService->isRightUser($this->getUser(), $comment)) {
            $this->addFlash('warning','Vous n\'avez pas les droits.');

            return $this->redirectToRoute('app_build_show', ['id' => $comment->getBuild()->getId()]);
        }

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_build_show', ['id' => $comment->getBuild()->getId()]);
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/comment/{id}/delete")
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, Comment $comment, CheckRightService $rightService): Response
    {
        $buildId = $comment->getBuild()->getId();

        if (!$rightService->isRightUser($this->getUser(), $comment)) {
            $this->addFlash('warning','Vous n\'avez pas les droits.');

            return $this->redirectToRoute('app_build_show', ['id' => $comment->getBuild()->getId()]);
        }

        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire à été supprimé correctement');
        }

        return $this->redirectToRoute('app_build_show', ['id' => $buildId]);
    }
}
