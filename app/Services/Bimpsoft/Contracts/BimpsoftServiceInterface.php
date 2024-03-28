<?php

namespace App\Services\Bimpsoft\Contracts;

interface BimpsoftServiceInterface
{
    /**
     * Get products from crm
     *
     * @param int $page
     * @return array
     */
    public function getProducts(int $page = 1): array;
}
