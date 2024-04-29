<?php

namespace App\Services\Bimpsoft;

use App\Services\Bimpsoft\Contracts\BimpsoftServiceInterface;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

/**
 * Class BimpsoftService
 */
class BimpsoftService implements BimpsoftServiceInterface
{
    /**
     * @var string
     */
    protected const API_URL = 'https://app.bimpsoft.com';

    /**
     * @var string
     */
    private string $accessToken;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        if (empty(config('bimpsoft.email')) || empty(config('bimpsoft.password'))) {
            throw new Exception('Email and password must be set in the .env file');
        }
    }

    /**
     * Get products from crm
     *
     * @param int $page
     * @return array
     * @throws RequestException
     */
    public function getProducts(int $page = 1): array
    {
        $this->getTokenIfNotSet();

        $limit = 100;

        $response = Http::withHeader('access-token', $this->accessToken)->post(self::API_URL . '/org2/nomenclature/api-readList', [
            'pagination' => [
                'count'  => $limit,
                'offset' => ($page - 1) * $limit
            ]
        ]);

        $response->throwIfServerError();
        $response->throwIf(!($response->json('success') ?? false));

        return $response->json('data') ?? [];
    }

    /**
     * Get leftovers
     *
     * @param array $uuids
     * @param string|null $warehouseUuid
     * @return mixed
     * @throws RequestException
     */
    public function getLeftovers(array $uuids, string $warehouseUuid = null): array
    {
        $this->getTokenIfNotSet();
        $limit = count($uuids);

        $response = Http::withHeader('access-token', $this->accessToken)->post(self::API_URL . '/org2/nomenclature/api-readLeftovers', [
            'warehouseUuid'     => $warehouseUuid ?? config('bimpsoft.warehouse_uuid'),
            'nomenclatureUuids' => $uuids,
            'periodable'        => null,
            'pagination'        => [
                'count'  => $limit,
                'offset' => 0
            ]
        ]);

        $response->throwIfServerError();
        $response->throwIf(!($response->json('success') ?? false));

        return $response->json('data') ?? [];
    }

    /**
     * Get prices
     *
     * @return mixed
     * @throws RequestException
     */
    public function getPrices(): array
    {
        $this->getTokenIfNotSet();

        $response = Http::withHeader('access-token', $this->accessToken)->post(self::API_URL . '/org2/warehouse/readNomenclatures', [
            'warehouseUuid' => config('bimpsoft.warehouse_uuid'),
            'пагинация'     => [
                'индекс' => 0,
                'первые' => 0
            ]
        ]);

        $response->throwIfServerError();
        $response->throwIf(!($response->json('success') ?? false));

        return $response->json('data') ?? [];
    }

    /**
     * Send order to bimpsoft
     *
     * @param array $data
     * @return string
     * @throws RequestException
     * @throws Exception
     */
    public function sendOrder(array $data): string
    {
        if (empty($data['stocks'])) {
            throw new Exception('Products not found');
        }

        $this->getTokenIfNotSet();

        $payload = [
            'projectUuid'        => config('bimpsoft.project_uuid'),
            'managerUuid'        => config('bimpsoft.manager_uuid'),
            'contractUuid'       => config('bimpsoft.contract_uuid'),
            'lineOfBusinessUuid' => config('bimpsoft.line_of_business_uuid'),
            'organizationUuid'   => config('bimpsoft.organization_uuid'),
            'warehouseUuid'      => config('bimpsoft.warehouse_uuid'),
            'customerUuid'       => config('bimpsoft.customer_uuid'),
            'statusUuid'         => config('bimpsoft.status_uuid'),
            'addVAT'             => false,
            'setUpByContract'    => false,
        ];

        $payload = array_merge($payload, $data);

        $response = Http::withHeader('access-token', $this->accessToken)->post(self::API_URL . '/org2/invoiceForCustomerPayment/api-insert', $payload);
        $response->throwIf(!($response->json('success') ?? false));

        if (empty($response->json('data')['uuid'])) {
            throw new Exception('Order not created');
        }

        return $response->json('data')['uuid'];
    }

    /**
     * @throws RequestException
     * @throws Exception
     */
    protected function getTokenIfNotSet(): void
    {
        if (isset($this->accessToken)) {
            return;
        }

        $response = Http::post(self::API_URL . '/org2/auth/api-login', [
            'email'    => config('bimpsoft.email'),
            'password' => config('bimpsoft.password'),
        ]);

        $response->throwIfServerError();
        $response->throwIf(!($response->json('success') ?? false));

        if (empty($response->json('data')['accessToken'])) {
            throw new Exception('Invalid credentials');
        }

        $token = $response->json('data')['accessToken'];

        $response = Http::withHeader('access-token', $token)->post(self::API_URL . '/org2/auth/api-selectCompany', [
            'uuid' => config('bimpsoft.company')
        ]);

        $response->throwIfServerError();
        $response->throwIf(!($response->json('success') ?? false));

        if (empty($response->json('data')['companyAccessToken'])) {
            throw new Exception('Invalid credentials');
        }

        $this->accessToken = $response->json('data')['companyAccessToken'];
    }
}
