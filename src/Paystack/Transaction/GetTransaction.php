<?php

namespace Paystack\Transaction;

use Gbowo\Adapter\Paystack\Traits\VerifyHttpStatusResponseCode;
use Gbowo\Plugin\AbstractPlugin;
use function GuzzleHttp\json_decode;

class GetTransaction extends AbstractPlugin
{

    use VerifyHttpStatusResponseCode;

    const SINGLE_TRANSACTION_ENDPOINT = "/transaction/:identifier";

    protected $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getPluginAccessor(): string
    {
        return "getTransaction";
    }

    public function handle(string $transactionReference)
    {
        $link = $this->baseUrl . str_replace(":identifier", $transactionReference, self::SINGLE_TRANSACTION_ENDPOINT);

        $response = $this->adapter->getHttpClient()
            ->get($link);

        $this->verifyResponse($response);

        return json_decode($response->getBody(), true)["data"];
    }
}
