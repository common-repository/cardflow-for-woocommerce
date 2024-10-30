<?php

namespace Cardflow\Client\Services;

use Cardflow\Client\Exceptions\ApiException;
use Cardflow\Client\Exceptions\MissingParameterException;
use Cardflow\Client\Resources\GiftCard;
use Cardflow\Client\Resources\Transaction;

final class GiftCardService extends AbstractService
{
    protected const API_PATH = 'giftcards';

    protected function getResourceClassPath(): string
    {
        return GiftCard::class;
    }

    /**
     * Retrieve a GiftCard from the API.
     * This method adds the GiftCard code to the GiftCard
     * resource, because it is not returned from the API.
     * @param string $id Code of the GiftCard
     * @return GiftCard
     * @throws ApiException
     */
    public function get(string $id): GiftCard
    {
        $id = GiftCard::cleanCode($id);
        $path = $this->buildApiPath([$id]);
        $response = $this->httpClient->request('GET', $path);

        $resource = $this->parseApiResponse($response);
        $resource['code'] = $id;

        return new GiftCard($this->httpClient, (array)$resource);
    }

    /**
     * @param string $id
     * @param array<string,string|bool|int> $options
     * @return Transaction
     * @throws ApiException
     * @throws MissingParameterException
     */
    public function redeem(string $id, array $options = []): Transaction
    {
        $id = GiftCard::cleanCode($id);
        $path = $this->buildApiPath([$id, 'redeem']);
        $response = $this->httpClient->request('POST', $path, $options);
        $resource = $this->parseApiResponse($response);

        return new Transaction($this->httpClient, (array)$resource);
    }

    /**
     * @param string $id
     * @param array<string,string|bool|int> $options
     * @return Transaction
     * @throws ApiException
     * @throws MissingParameterException
     */
    public function issue(string $id, array $options = []): Transaction
    {
        $id = GiftCard::cleanCode($id);
        $path = $this->buildApiPath([$id, 'issue']);
        $response = $this->httpClient->request('POST', $path, $options);
        $resource = $this->parseApiResponse($response);

        return new Transaction($this->httpClient, (array)$resource);
    }

    public function validatekey(string $apikey, array $options = [])
    {
        $path = $this->buildApiPath([trim($apikey), 'validateapikey']);
        $response = $this->httpClient->request('GET', $path, $options);
        $resource = $this->parseApiResponse($response);
        return $resource;
    }
}
