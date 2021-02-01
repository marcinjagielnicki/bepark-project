<?php


namespace App\GameDictionaries\Rawg;


use App\GameDictionaries\Rawg\Exceptions\ClientException;
use App\GameDictionaries\Rawg\Exceptions\ClientResponseException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Client
{

    public const REQUEST_TYPE_GET = 'get';

    protected \GuzzleHttp\Client $httpClient;

    protected string $apiKey;

    public function __construct(\GuzzleHttp\Client $httpClient, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
    }


    /**
     * @param string $path
     * @param array $params
     * @param string $type
     * @return array
     * @throws ClientException
     * @throws ClientResponseException
     */
    public function makeRequest(string $path, array $params = [], string $type = self::REQUEST_TYPE_GET): array
    {
        $requestParams = [
            'query' => [
                'key' => $this->apiKey
            ]
        ];

        if(self::REQUEST_TYPE_GET === $type) {

            // Disallow app to set api key directly in request.
            if(isset($params['key'])) {
                unset($params['key']);
            }

            // Merge request params with default request params.
            $requestParams['query'] = array_merge($requestParams['query'], $params);
        }
        try {
            $results = $this->httpClient->request($type, $path, $requestParams);
            return $this->parseResponse($results);
        } catch (GuzzleException $e) {
            throw new ClientException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param ResponseInterface $response
     * @return array
     * @throws ClientResponseException
     */
    protected function parseResponse(ResponseInterface $response): array
    {
        $responseArray = json_decode((string)$response->getBody(), true);

        if(!is_array($responseArray)) {
            throw new ClientResponseException($responseArray, 'Could not parse Rawg response');
        }

        return $responseArray;
    }

}
