<?php


namespace App\Services;


use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;

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
    /**
     * @var string
     */
    protected $token;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @var array
     */
    protected $params = [];


    /**
     * TODO нужно будет учесть пагинацию
     *
     * @param string $url
     * @param array $params
     * @return APIClient
     * @throws Exception
     */
    public function get(string $url, array $params = [])
    {
        if (!empty($params)) {
            $this->setParams($params);
        }

        try {

            $result = $this->client->get($url, ['query' => $this->getParams()]);

            if ($result) {
                return $this->setResult($result->getBody());
            }

        } catch (RequestException $e) {

            /** @var ResponseInterface $response */
            $response = $e->getResponse();

            if (config('app.debug')) {
                dump($response->getStatusCode()); // HTTP status code
                dump($response->getReasonPhrase()); // Message
                dump((string)$response->getBody()); // Body
                dump($response->getHeaders()); // Headers array
                dump($response->hasHeader('Content-Type')); // Is the header presented
                dump($response->getHeader('Content-Type')[0]); // Concrete header value
            }

            if ($response->getStatusCode() !== 200) {
                $this->processCallException($response);
            }
        }
    }

    public function setParams($params)
    {
        $this->params = array_merge($this->params, $params);

        return $this;
    }

    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param string $resource
     * @return APIClient
     */
    public function setResult(string $resource): self
    {
        $this->result_data = json_decode($resource);
        return $this;
    }

    /**
     *   Returns call resource for last call.
     *
     * @return null|array
     */
    public function getResult(): ?array
    {
        return $this->result_data;
    }


    /**
     * @param ResponseInterface $response
     * @throws Exception
     */
    private function processCallException(ResponseInterface $response)
    {
        $result_code = $response->getStatusCode();
        $message = $response->getReasonPhrase();

        switch ($result_code) {
            case 503:
                throw new Exception('APIClient: Service is temporarily unavailable.', $result_code);
            case 500:
                throw new Exception('APIClient: Internal server error occured.', $result_code);
            case 429:
                throw new Exception("APIClient: Rate limit for this API key was exceeded. {$message}", $result_code);
            case 415:
                throw new Exception("APIClient: Unsupported media type. {$message}", $result_code);
            case 404:
                throw new Exception("APIClient: Not Found. {$message}", $result_code);
            case 403:
                throw new Exception("APIClient: Forbidden. {$message}", $result_code);
            case 401:
                throw new Exception("APIClient: Unauthorized. {$message}", $result_code);
            case 400:
                throw new Exception("APIClient: Request is invalid. {$message}", $result_code);
            default:
                if ($result_code >= 400) {
                    throw new Exception("APIClient: Unspecified error occured ({$result_code}). {$message}",
                        $result_code);
                }
        }
    }
}
