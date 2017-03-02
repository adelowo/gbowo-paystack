<?php

namespace Paystack\Subscription;

use Gbowo\Adapter\Paystack\Traits\VerifyHttpStatusResponseCode;
use function GuzzleHttp\json_decode;
use function Gbowo\toQueryParams;
use Gbowo\Plugin\AbstractPlugin;

class GetAllSubscriptions extends AbstractPlugin
{
    use VerifyHttpStatusResponseCode;

    const GET_ALL_SUBSCRIPTIONS_ENDPOINT = "/subscription";

    protected $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getPluginAccessor(): string
    {
        return "getAllSubscriptions";
    }

    public function handle(array $params = [])
    {
        $queryParams = toQueryParams($params);

        $response = $this->adapter->getHttpClient()
            ->get($this->baseUrl . self::GET_ALL_SUBSCRIPTIONS_ENDPOINT . $queryParams);

        $this->verifyResponse($response);

        return array_slice(
            json_decode($response->getBody(), true),
            2
        );
    }
}
