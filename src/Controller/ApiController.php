<?php

namespace App\Controller;

use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/oauth")
     */
    public function oauth(ApiService $apiService)
    {
        $provider = $apiService->getProvider();

        return $this->redirect($provider->getAuthorizationUrl());
    }
}
