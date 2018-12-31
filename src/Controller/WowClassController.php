<?php

namespace App\Controller;

use App\Entity\WowClass;
use App\Form\WowClassType;
use App\Repository\WowCLassRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wow/class")
 */
class WowClassController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function index(WowCLassRepository $wowCLassRepository): Response
    {
        return $this->render('wow_class/index.html.twig', ['wow_classes' => $wowCLassRepository->findAll()]);
    }

    /**
     * @Route("/new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $wowClass = new WowClass();
        $form = $this->createForm(WowClassType::class, $wowClass);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($wowClass);
            $entityManager->flush();

            return $this->redirectToRoute('app_wowclass_index');
        }

        return $this->render('wow_class/new.html.twig', [
            'wow_class' => $wowClass,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function show(WowClass $wowClass): Response
    {
        return $this->render('wow_class/show.html.twig', ['wow_class' => $wowClass]);
    }

    /**
     * @Route("/{id}/edit", methods={"GET","POST"})
     */
    public function edit(Request $request, WowClass $wowClass): Response
    {
        $form = $this->createForm(WowClassType::class, $wowClass);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_wowclass_index', ['id' => $wowClass->getId()]);
        }

        return $this->render('wow_class/edit.html.twig', [
            'wow_class' => $wowClass,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     */
    public function delete(Request $request, WowClass $wowClass): Response
    {
        if ($this->isCsrfTokenValid('delete'.$wowClass->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($wowClass);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_wowclass_index');
    }
}
