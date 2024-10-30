<?php

namespace Cardflow\Client\Resources;

use Cardflow\Client\Exceptions\MissingParameterException;
use Cardflow\Client\HttpClient\CardflowHttpClientInterface;

final class Transaction extends AbstractResource
{

    /**
     * Transaction constructor.
     * @param CardflowHttpClientInterface $httpClient
     * @param array<string|int|bool> $data
     * @throws MissingParameterException
     */
    public function __construct(CardflowHttpClientInterface $httpClient, array $data = [])
    {
        parent::__construct($httpClient, $data);

        $this->container['id'] = $data['id'] ?? null;
        $this->container['amount'] = $data['amount'] ?? 0;
        $this->container['currency'] = $data['currency'] ?? null;
        $this->container['status'] = $data['status'] ?? null;
        $this->container['type'] = $data['type'] ?? null;
        $this->container['description'] = $data['description'] ?? null;
        $this->container['is_capturable'] = $data['is_capturable'] ?? false;
        $this->container['captured_at'] = $data['captured_at'] ?? null;
        $this->container['created_at'] = $data['created_at'] ?? null;
    }

    public function getId(): ?string
    {
        return $this->container['id'];
    }

    public function getAmount(): int
    {
        return $this->container['amount'];
    }

    public function getCurrency(): ?string
    {
        return $this->container['currency'];
    }

    public function getStatus(): ?string
    {
        return $this->container['status'];
    }

    public function getType(): ?string
    {
        return $this->container['type'];
    }

    public function getDescription(): ?string
    {
        return $this->container['description'];
    }

    public function getCapturedAt(): ?string
    {
        return $this->container['captured_at'];
    }

    public function getCreatedAt(): ?string
    {
        return $this->container['created_at'];
    }

    public function isCapturable(): bool
    {
        return $this->container['is_capturable'] === true;
    }
}
