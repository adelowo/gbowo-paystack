<?php

namespace Paystack\Transaction;

use Gbowo\Plugin\AbstractPlugin;
use function GuzzleHttp\json_decode;
use Gbowo\Exception\InvalidHttpResponseException;

class GetTransaction extends AbstractPlugin
{
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

        if ($response->getStatusCode() !== 200) {
            throw new InvalidHttpResponseException(
                "An error occurred while fetching the customer's details. Expected 200, got {$response->getStatusCode()}"
            );
        }

        return json_decode($response->getBody(), true)["data"];
    }
}
