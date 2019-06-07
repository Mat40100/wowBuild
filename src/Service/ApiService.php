<?php
/**
 * Created by PhpStorm.
 * User: dolhen
 * Date: 18/01/19
 * Time: 16:23
 */

namespace App\Service;

use GuzzleHttp\Client;

class ApiService
{
    private $datas;
    private $gameDataClient;
    private $gameProfileClient;

    public function __construct()
    {
        $this->datas = [
            'response_type' => 'code',
            'client_id' => getenv("WOW_ID"),
            'redirect_uri' => getenv("SITE_URL")."oauth",
        ];

        $this->gameDataClient = new Client(['base_uri' => 'https://eu.api.blizzard.com']);
        $this->gameProfileClient = new Client(['base_uri'=>'https://eu.battle.net']);
    }

    public function getState()
    {
        return random_bytes(15);
    }

    /**
     * @return array
     */
    public function getDatas(): array
    {
        return $this->datas;
    }

    /**
     * @return Client
     */
    public function getGameDataClient(): Client
    {
        return $this->gameDataClient;
    }

    /**
     * @param Client $gameDataClient
     */
    public function setGameDataClient(Client $gameDataClient): void
    {
        $this->gameDataClient = $gameDataClient;
    }

    /**
     * @return Client
     */
    public function getGameProfileClient(): Client
    {
        return $this->gameProfileClient;
    }

    /**
     * @param Client $gameProfileClient
     */
    public function setGameProfileClient(Client $gameProfileClient): void
    {
        $this->gameProfileClient = $gameProfileClient;
    }


}