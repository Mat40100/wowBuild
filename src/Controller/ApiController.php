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
        $code = $request->get('code');
        $provider = $apiService->getProvider();

        if ($code === null) {
            return $this->redirect($provider->getAuthorizationUrl());
        }

        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $code
        ]);

        $client = new Client();

        $req = $client->request('GET','https://eu.api.blizzard.com/data/wow/playable-specialization/index?namespace=static-us&locale=fr_EU&access_token='.$accessToken->getToken());

        dump($req);
        die();

        return $this->redirectToRoute('app_api_oauth');
    }
}
