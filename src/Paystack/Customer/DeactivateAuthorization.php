<?php

namespace Paystack\Customer;

use Gbowo\Adapter\Paystack\Traits\VerifyHttpStatusResponseCode;
use Gbowo\Plugin\AbstractPlugin;
use function Gbowo\toQueryParams;
use function GuzzleHttp\json_decode;

class DeactivateAuthorization extends AbstractPlugin
{
    use VerifyHttpStatusResponseCode;

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

        $this->verifyResponse($response);

        return json_decode($response->getBody(), true)["status"];
    }
}
