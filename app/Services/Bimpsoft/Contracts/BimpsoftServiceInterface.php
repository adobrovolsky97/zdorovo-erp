<?php

namespace App\Services\Bimpsoft\Contracts;

use Illuminate\Http\Client\RequestException;

interface BimpsoftServiceInterface
{
    /**
     * Get products from crm
     *
     * @param int $page
     * @return array
     */
    public function getProducts(int $page = 1): array;

    /**
     * Send order to bimpsoft
     *
     * @param array $data
     * @return string
     */
    public function sendOrder(array $data): string;

    /**
     * Get leftovers
     *
     * @param array $uuids
     * @return mixed
     * @throws RequestException
     */
    public function getLeftovers(array $uuids): array;

    /**
     * Get prices
     *
     * @return mixed
     */
    public function getPrices(): array;
}
