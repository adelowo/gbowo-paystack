<?php

namespace Paystack\Bank;

use Gbowo\Adapter\Paystack\Traits\VerifyHttpStatusResponseCode;
use function GuzzleHttp\json_decode;
use function Gbowo\toQueryParams;
use Gbowo\Plugin\AbstractPlugin;

class GetCardBIN extends AbstractPlugin
{
    use VerifyHttpStatusResponseCode;

    protected $baseUrl;

    const RESOLVE_CARD_BIN_ENDPOINT = "/decision/bin/:bin"; 

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getPluginAccessor(): string
    {
        return "getCardBIN";
    }

    public function handle(string $bin)
    {
        $link = $this->baseUrl . str_replace(":bin", $bin, self::RESOLVE_CARD_BIN_ENDPOINT);

        $response = $this->adapter->getHttpClient()
            ->get($link);

        $this->verifyResponse($response);

        return json_decode($response->getBody(), true)["data"];
    }
}
