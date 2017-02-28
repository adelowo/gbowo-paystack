<?php

namespace Paystack\Customer;

use Gbowo\Exception\InvalidHttpResponseException;
use Gbowo\Plugin\AbstractPlugin;
use function Gbowo\toQueryParams;
use function GuzzleHttp\json_decode;

class DeactivateAuthorization extends AbstractPlugin
{
    const DEACTIVATE_AUTHORIZATION_ENDPOINT = "/customer/deactivate_authorization";

    protected $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getPluginAccessor(): string
    {
        return "deactivateAuthorization";
    }

    public function handle(string $authorizationCode)
    {
        $queryParams = toQueryParams(["authorization_code" => $authorizationCode]);

        $link = $this->baseUrl . self::DEACTIVATE_AUTHORIZATION_ENDPOINT . $queryParams;

        $response = $this->adapter->getHttpClient()
            ->post($link);

        if ($response->getStatusCode() !== 200) {
            throw new InvalidHttpResponseException(
                "An invalid HTTP response code was received. Expected 200, got {$response->getStatusCode()}"
            );
        }

        return json_decode($response->getBody(), true)["status"];
    }
}
