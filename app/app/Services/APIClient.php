<?php


namespace App\Services;


use GuzzleHttp\Client;

/**
 * TODO сделаем простой клиент, не будем обвешивать промисами проверками и тд
 *
 * Class APIClient
 * @package App\Services
 */
class APIClient
{
    /**
     * @var Client
     */
    private $client;

    /** @var array $result_data */
    protected $result_data;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }


    /**
     * @param string $url
     * @param array $params
     * @return APIClient
     */
    public function get(string $url, array $params = [])
    {
        $res = $this->client->get($url, $params);

        return $this->setResource($res->getBody());
    }

    /**
     * @param string $resource
     * @return APIClient
     */
    public function setResource(string $resource): self
    {
        $this->result_data = json_decode($resource);
        return $this;
    }

    /**
     *   Returns call resource for last call.
     *
     * @return array
     */
    public function getResult(): array
    {
        return $this->result_data;
    }
}
