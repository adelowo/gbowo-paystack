<?php

namespace Paystack\Bank;

use Gbowo\Adapter\Paystack\Traits\VerifyHttpStatusResponseCode;
use function GuzzleHttp\json_decode;
use function Gbowo\toQueryParams;
use Gbowo\Plugin\AbstractPlugin;

class ListBanks extends AbstractPlugin
{
    use VerifyHttpStatusResponseCode;

    protected $baseUrl;

    const LIST_BANKS_ENDPOINT = "/bank";

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getPluginAccessor(): string
    {
        return "listBanks";
    }

    public function handle(array $filter = [])
    {
        $link = $this->baseUrl. self::LIST_BANKS_ENDPOINT . toQueryParams($filter);

        $response = $this->adapter->getHttpClient()
            ->get($link);

        $this->verifyResponse($response);

        return json_decode($response->getBody(), true)["data"];
    }
}
