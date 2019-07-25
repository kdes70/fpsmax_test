<?php


namespace App\Services;


use GuzzleHttp\Client;

class PandaScoreAPI extends APIClient
{

    public function __construct(Client $client)
    {
        parent::__construct($client);

        $this->setParams(['token' => config('services.pandascore.token')]);
    }
}
