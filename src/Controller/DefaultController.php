<?php

namespace App\Controller;

use App\Entity\Build;
use App\Entity\WowClass;
use App\Service\BuildSortService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(BuildSortService $sortService)
    {
        $repo = $this->getDoctrine()->getRepository(Build::class);
        $builds = $repo->findLast(5);
        $all = $repo->findBy(['isActive'=>true]);
        $allClass = $this->getDoctrine()->getRepository(WowClass::class)->findAll();

        $best = $sortService->topSort($all, 3);

       return $this->render('frontPage/front.html.twig', ['builds' => $builds, 'best' => $best, 'allClass' => $allClass]);
    }

    /**
     * @Route("/topThree/{id}")
     */
    public function xmlTopThree(WowClass $class, Request $request, BuildSortService $sortService)
    {
        if ($request->isXmlHttpRequest()) {
            $repo = $this->getDoctrine()->getRepository(Build::class);
            $all = $repo->findByClass($class);
            sleep(0.5);
            if(count($all) > 0) {
                $best = $sortService->topSort($all, 3);

                return $this->render('frontPage/top.html.twig', ['best' => $best, 'class' => $class]);
            }

            return $this->render('frontPage/top.html.twig', ['class' => $class]);
        }
    }
}
