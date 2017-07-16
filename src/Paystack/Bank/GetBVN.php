<?php

namespace Paystack\Bank;

use Exception;
use Gbowo\Adapter\Paystack\Traits\VerifyHttpStatusResponseCode;
use function GuzzleHttp\json_decode;
use function Gbowo\toQueryParams;
use Gbowo\Plugin\AbstractPlugin;

class GetBVN extends AbstractPlugin
{
    use VerifyHttpStatusResponseCode;

    protected $baseUrl;

    const GET_BVN_ENDPOINT = "/bank/resolve_bvn/:bvn";

    const VALID_BVN_LENGTH = 11;

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
        if (mb_strlen($bvn) !== self::VALID_BVN_LENGTH) {
            throw new Exception(
                "Invalid BVN.. A valid BVN should contain only 11 digits"
            );
            
        }

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
