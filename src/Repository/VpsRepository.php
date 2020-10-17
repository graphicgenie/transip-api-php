<?php

namespace Transip\Api\Library\Repository;

use Transip\Api\Library\Entity\Vps;

class VpsRepository extends ApiRepository
{
    public const RESOURCE_NAME = 'vps';

    /**
     * @return Vps[]
     */
    public function getAll(): array
    {
        $vpss      = [];
        $response  = $this->httpClient->get($this->getResourceUrl());
        $vpssArray = $this->getParameterFromResponse($response, 'vpss');

        foreach ($vpssArray as $vpsArray) {
            $vpss[] = new Vps($vpsArray);
        }

        return $vpss;
    }

    /**
     * @param int $page
     * @param int $itemsPerPage
     * @return Vps[]
     */
    public function getSelection(int $page, int $itemsPerPage): array
    {
        $vpss      = [];
        $query     = ['pageSize' => $itemsPerPage, 'page' => $page];
        $response  = $this->httpClient->get($this->getResourceUrl(), $query);
        $vpssArray = $this->getParameterFromResponse($response, 'vpss');

        foreach ($vpssArray as $vpsArray) {
            $vpss[] = new Vps($vpsArray);
        }

        return $vpss;
    }

    public function getByName(string $name): Vps
    {
        $response = $this->httpClient->get($this->getResourceUrl($name));
        $vps      = $this->getParameterFromResponse($response, 'vps');

        return new Vps($vps);
    }

    public function getByTagNames(array $tags): array
    {
        $tags      = implode(',', $tags);
        $query     = ['tags' => $tags];
        $response  = $this->httpClient->get($this->getResourceUrl(), $query);
        $vpssArray = $this->getParameterFromResponse($response, 'vpss');

        $vpss = [];
        foreach ($vpssArray as $vpsArray) {
            $vpss[] = new Vps($vpsArray);
        }

        return $vpss;
    }

    public function start(string $vpsName): void
    {
        $this->httpClient->patch($this->getResourceUrl($vpsName), ['action' => 'start']);
    }

    public function stop(string $vpsName): void
    {
        $this->httpClient->patch($this->getResourceUrl($vpsName), ['action' => 'stop']);
    }

    public function reset(string $vpsName): void
    {
        $this->httpClient->patch($this->getResourceUrl($vpsName), ['action' => 'reset']);
    }

}
