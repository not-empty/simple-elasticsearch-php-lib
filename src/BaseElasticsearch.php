<?php

namespace SimpleElasticsearch;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\ClientException;
use Exception;

class BaseElasticsearch
{
    public $connectionOptions = [];

    /**
     * constructor
     * @param string $elasticConfig
     */
    public function __construct(
        string $elasticHost = 'localhost:9200'
    ) {
        $this->elasticHost = $elasticHost;
    }
    
    /**
     * method sendRequest
     * send request to elasticsearch
     * @return mixed
     */
    public function sendRequest(
        string $method,
        string $url,
        string $uri,
        array $body = [],
        array $header = []
    ) {
        try {
            $headers = $this->prepareHeader(
                $header
            );
            $body = $this->prepareBody(
                $body
            );
            $url  = $this->prepareUrl(
                $url,
                $uri
            );

            $response = $this->newGuzzle()->$method(
                $url,
                array_merge(
                    $headers,
                    $body,
                    $this->connectionOptions
                )
            );
            return $response->getBody()
                ->getContents();
        } catch (ClientException $exception) {
            return [
                'message' => json_decode(
                    $exception->getResponse()
                        ->getBody(),
                    true
                ),
                'error_code' => $exception->getResponse()
                    ->getStatusCode(),
            ];
        } catch (Exception $exception) {
            return [
                'message' => $exception->getMessage() ?? 'Request error',
                'error_code' => $exception->getCode() ?? 500,
            ];
        }
    }

    /**
     * method prepareBody
     * prepare body request before send
     * @param array $body
     * @return array
     */
    public function prepareBody(array $body = []): array
    {
        if (!empty($body)) {
            return [
                'json' => $body
            ];
        }
        return $body;
    }

    /**
     * method prepareHeader
     * prepare header request before send
     * @param array $header
     * @return array
     */
    public function prepareHeader(array $header): array
    {
        return [
            'headers' => $header,
        ];
    }

    /**
     * method prepareUrl
     * prepare the endpoint before send
     * @param string $url
     * @param string $uri
     * @return string
     */
    public function prepareUrl(string $url, string $uri): string
    {
        $protocol = '';
        if (strpos($url, 'http') !== false) {
            $url = explode('//', $url);

            $protocol = $url[0] . '//';
            $url = $url[1];
        }

        $url = str_replace('/', '', $url);

        if (strpos($uri, '/') === false || strpos($uri, '/') > 0) {
            $uri = "/$uri";
        }

        return "$protocol$url$uri";
    }

    /**
     * decode response if needed
     * @param mixed $response
     * @return array $response
     */
    public function decodeResponse(
        $response
    ) : array {
        if (is_array($response)) {
            return $response;
        }
        return json_decode($response, true);
    }

    /**
     * set connection options for request
     * @param array $options
     * @return array
     */
    public function setConnectionOptions(array $options): array
    {
        $this->connectionOptions = $options;
        return $this->connectionOptions;
    }

    /**
     * method calculateFrom
     * calculate from to paginate
     * @param int $page
     * @return int
     */
    public function calculateFrom(
        int $page
    ): int {
        return ($page - 1) * 25;
    }

    /**
     * @codeCoverageIgnore
     * create guzzle client instance
     * @return \GuzzleHttp\Client
     */
    public function newGuzzle()
    {
        return new Guzzle();
    }
}
