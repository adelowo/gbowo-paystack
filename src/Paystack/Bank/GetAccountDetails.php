<?php

namespace Paystack\Bank;

use Gbowo\Adapter\Paystack\Traits\VerifyHttpStatusResponseCode;
use function GuzzleHttp\json_decode;
use function Gbowo\toQueryParams;
use Gbowo\Plugin\AbstractPlugin;

class GetAccountDetails extends AbstractPlugin
{
    use VerifyHttpStatusResponseCode;

    protected $baseUrl;

    const GET_ACCOUNT_DETAILS_ENDPOINT = "/bank/resolve";

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getPluginAccessor(): string
    {
        return "getAccountDetails";
    }

    public function handle(array $params)
    {
        $link = $this->baseUrl. self::GET_ACCOUNT_DETAILS_ENDPOINT . toQueryParams($params);

        $response = $this->adapter->getHttpClient()
            ->get($link);

        $this->verifyResponse($response);

        return json_decode($response->getBody(), true)["data"];
    }
}
