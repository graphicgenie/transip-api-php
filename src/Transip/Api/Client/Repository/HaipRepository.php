<?php

namespace Transip\Api\Client\Repository;

use Transip\Api\Client\Entity\Haip;

class HaipRepository extends ApiRepository
{
    protected function getRepositoryResourceNames(): array
    {
        return ['haips'];
    }

    /**
     * @return Haip[]
     */
    public function getAll(): array
    {
        $haips      = [];
        $response   = $this->httpClient->get($this->getResourceUrl());
        $haipsArray = $response['haips'] ?? [];

        foreach ($haipsArray as $haipArray) {
            $haips[] = new Haip($haipArray);
        }

        return $haips;
    }

    public function findByDescription(string $description): array
    {
        $haips = [];
        foreach($this->getAll() as $haip) {
            if ($haip->getDescription() === $description) {
                $haips[] = $haip;
            }
        }

        return $haips;
    }

    public function getByName(string $name): Haip
    {
        $response = $this->httpClient->get($this->getResourceUrl($name));
        $haip     = $response['haip'] ?? null;

        var_dump($haip);

        return new Haip($haip);
    }

    public function order(
        string $productName,
        ?string $description
    ): void {
        $parameters = ['productName' => $productName];

        if ($description) {
            $parameters['description'] = $description;
        }

        $this->httpClient->post($this->getResourceUrl(), $parameters);
    }

    public function update(Haip $vps): void
    {
        $this->httpClient->put($this->getResourceUrl($vps->getName()), ['vps' => $vps]);
    }

    public function cancel(string $name, string $endTime): void
    {
        $this->httpClient->delete($this->getResourceUrl($name), ['endTime' => $endTime]);
    }
}