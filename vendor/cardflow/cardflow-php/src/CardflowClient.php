<?php

namespace Cardflow\Client;

use Cardflow\Client\Exceptions\ApiException;
use Cardflow\Client\Factories\ServiceFactory;
use Cardflow\Client\HttpClient\CardflowHttpClient;
use Cardflow\Client\HttpClient\CardflowHttpClientInterface;
use Cardflow\Client\Services\GiftCardService;

/**
 * Class CardflowClient
 * @package Cardflow\Client
 * @property GiftCardService $giftCards
 */
final class CardflowClient
{
    public const VERSION = '1.0.1';
    private const USER_AGENT_FORMAT = 'Cardflow/Cardflow-PHP/%s/PHP/%s/%s';

    /**
     * @var string
     */
    private string $apiEndpoint = 'https://api.cardflow.nl/api/v1/';

    /**
     * @var array<string, string>
     */
    private array $apiHeaders = [];

    /**
     * @var CardflowHttpClientInterface
     */
    private CardflowHttpClientInterface $httpClient;

    /**
     * @var ServiceFactory
     */
    private ServiceFactory $serviceFactory;

    /**
     * CardflowClient constructor.
     * @param string $apiKey
     * @param array<string, string> $options
     * @param CardflowHttpClientInterface|null $httpClient
     */
    public function __construct(string $apiKey, $options = [], ?CardflowHttpClientInterface $httpClient = null)
    {
        if (isset($options['api_endpoint'])) {
            $this->setApiEndpoint($options['api_endpoint']);
        }

        if (isset($options['api_headers']) && is_array($options['api_headers'])) {
            $this->setApiHeaders($options['api_headers']);
        }

        $this->setHttpClient($httpClient);
        $this->httpClient->setAccessToken($apiKey);
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function __get(string $name)
    {
        if (false === isset($this->serviceFactory)) {
            $this->serviceFactory = new ServiceFactory($this->httpClient);
        }

        return $this->serviceFactory->__get($name);
    }

    public function validateApiKey( $apikey ): bool
    {
        try {
            $validated = $this->giftCards->validatekey( $apikey );
        } catch (ApiException $e) {
            return false;
        }
        return true;
    }

    /**
     * Set the HTTP client to our default (Curl) or use the user
     * specified client. This is useful for testing so we can use
     * the Guzzle Mock client.
     * @param CardflowHttpClientInterface|null $httpClient
     * @return CardflowClient
     */
    private function setHttpClient(?CardflowHttpClientInterface $httpClient = null): self
    {
        if ($httpClient !== null) {
            $this->httpClient = $httpClient;

            return $this;
        }

        $this->httpClient = new CardflowHttpClient(
            $this->apiEndpoint,
            10,
            2,
            array_merge(
                [
                'User-Agent' => $this->getUserAgent(CardflowHttpClient::getClientName()),
                'Accept' => 'application/json',
                ],
                $this->apiHeaders,
            )
        );

        return $this;
    }

    private function setApiEndpoint(string $endpoint): self
    {
        $this->apiEndpoint = $endpoint;

        return $this;
    }

    /**
     * @param array<string, string> $headers
     */
    private function setApiHeaders(array $headers): self
    {
        $this->apiHeaders = $headers;

        return $this;
    }

    private function getUserAgent(string $httpClientName): string
    {
        return sprintf(
            self::USER_AGENT_FORMAT,
            self::VERSION,
            phpversion(),
            $httpClientName
        );
    }
}
