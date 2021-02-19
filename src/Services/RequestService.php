<?php


namespace CloudFox\GetNet\Services;


use CloudFox\GetNet\Http\Requests\OptionsRequest;
use Exception;
use GuzzleHttp\Client;
use LogicException;

class RequestService
{

    protected Client $client;

    public function __construct()
    {

        $this->client = new Client([
            'http_errors' => false,
            'allow_redirects' => false
        ]);
    }

    /**
     * @param OptionsRequest $optionsRequest
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws Exception
     */
    public function sendRequest(OptionsRequest $optionsRequest)
    {

        $method = $optionsRequest->method;
        $url = $optionsRequest->url;
        $content = $optionsRequest->content;

        $options = $optionsRequest->headers;

        if (!in_array($method, [OptionsRequest::METHOD_GET, OptionsRequest::METHOD_POST, OptionsRequest::METHOD_PUT])) {

            throw new LogicException('O método "' . $method . '" não é permitido');
        }

        if ($method == OptionsRequest::METHOD_POST) {

            $response = $this->client->post($url, $options);

        } elseif ($method == OptionsRequest::METHOD_PUT) {

            $response = $this->client->put($url, $options);

        } else {

            $response = $this->client->get($url, $options);
        }

        $statusCode = $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();

        $unSerialized = [];

        switch ($statusCode) {
            case 200:
            case 201:
                $unSerialized = json_decode($responseBody);
                break;
            default:

                throw new Exception('Erro no processamento da API :: '.$responseBody, $statusCode);
        }

        return $unSerialized;
    }
}
