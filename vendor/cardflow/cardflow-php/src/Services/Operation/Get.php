<?php

namespace Cardflow\Client\Services\Operation;

use Cardflow\Client\Resources\AbstractResource;

trait Get
{

    public function get(string $id): AbstractResource
    {
        $path = $this->buildApiPath([$id]);
        $response = $this->httpClient->request('GET', $path);
        $resource = $this->parseApiResponse($response);
        $resourceClass = $this->getResourceClassPath();

        return new $resourceClass($this->httpClient, $resource);
    }
}
