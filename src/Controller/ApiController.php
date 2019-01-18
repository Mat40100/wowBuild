<?php

namespace App\Controller;

use App\Service\ApiService;
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

        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.
        echo 'Access Token: ' . $accessToken->getToken() . "<br>";
        echo 'Refresh Token: ' . $accessToken->getRefreshToken() . "<br>";
        echo 'Expired in: ' . $accessToken->getExpires() . "<br>";
        echo 'Already expired? ' . ($accessToken->hasExpired() ? 'expired' : 'not expired') . "<br>";

        // Using the access token, we may look up details about the
        // resource owner.
        $resourceOwner = $provider->getResourceOwner($accessToken);

        var_export($resourceOwner->toArray());

        die();

        return $this->redirectToRoute('app_api_oauth');
    }
}
