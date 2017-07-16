<?php

namespace Paystack\Bank;

use Gbowo\Adapter\Paystack\Traits\VerifyHttpStatusResponseCode;
use function GuzzleHttp\json_decode;
use function Gbowo\toQueryParams;
use Gbowo\Plugin\AbstractPlugin;

class GetBVN extends AbstractPlugin
{
    use VerifyHttpStatusResponseCode;

    protected $baseUrl;

    const GET_BVN_ENDPOINT = "/bank/resolve_bvn/:bvn";

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getPluginAccessor(): string
    {
        return "getBVN";
    }

    public function handle(string $bvn)
    {
        $link = $this->baseUrl. str_replace(":bvn", $bvn, self::GET_BVN_ENDPOINT);

        $response = $this->adapter->getHttpClient()
            ->get($link);

        $this->verifyResponse($response);

	//return both the data and meta keys from the array
        return array_slice(
            json_decode($response->getBody(), true),
            2
        );
    }
}
