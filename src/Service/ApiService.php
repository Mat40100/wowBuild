<?php
/**
 * Created by PhpStorm.
 * User: dolhen
 * Date: 18/01/19
 * Time: 16:23
 */

namespace App\Service;


use Depotwarehouse\OAuth2\Client\Provider\WowProvider;

class ApiService
{
    private $provider;

    public function __construct()
    {
        $this->provider = new WowProvider([
            'clientId' => getenv("WOW_ID"),
            'clientSecret' => getenv('WOW_SECRET'),
            'redirectUri' => getenv("SITE_URL")."oauth",
            'region' => 'eu'
        ]);
    }

    public function getProvider()
    {
        return $this->provider;
    }
}