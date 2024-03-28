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
     *
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

        $response = Http::withHeader('access-token', $this->accessToken)->post(self::API_URL . '/org2/nomenclature/api-readList', [
           'pagination' => [
               'count' => 200,
               'offset' => 0
           ]
        ]);
        dd($response->json());
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

        if (empty($response->json('data')['accessToken'])) {
            throw new Exception('Invalid credentials');
        }

        $token = $response->json('data')['accessToken'];

        $response = Http::withHeader('access-token', $token)->post(self::API_URL . '/org2/auth/api-selectCompany', [
            'uuid' => config('bimpsoft.company')
        ]);

        $response->throwIfServerError();

        if (empty($response->json('data')['companyAccessToken'])) {
            throw new Exception('Invalid credentials');
        }

        $this->accessToken = $response->json('data')['companyAccessToken'];
    }
}
