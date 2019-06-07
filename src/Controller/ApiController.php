<?php

namespace App\Controller;

use App\Service\ApiService;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/oauth")
     */
    public function oauth(Request $request, ApiService $apiService)
    {
       Request::createFromGlobals(
            'https://eu.battle.net/oauth/authorize',
            'GET',
            $apiService->getDatas()

        );

        $response = $profileClient->post('oauth/authorize', ['query' => $apiService->getDatas()]);
        dump($response);
        die();

        return $this->redirectToRoute('app_api_oauth');
    }
}
