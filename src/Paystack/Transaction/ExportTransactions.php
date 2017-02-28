<?php

namespace Paystack\Transaction;

use GuzzleHttp\json_decode;
use Gbowo\Plugin\AbstractPlugin;
use Psr\Http\Message\ResponseInterface;
use Gbowo\Exception\InvalidHttpResponseException;

class ExportTransactions extends AbstractPlugin
{
    const TRANSACTION_ENDPOINT = "/transaction/export";

    protected $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getPluginAccessor(): string
    {
        return "exportTransactions";
    }

    public function handle(array $params = [])
    {

        $link = $this->baseUrl . self::TRANSACTION_ENDPOINT . \Gbowo\toQueryParams($params);

        $response = $this->getExportedTransactions($link);

        if ($response->getStatusCode() !== 200) {
            throw new InvalidHttpResponseException(
                "An error occurred while your transactions were being exported"
            );
        }

        return json_decode($response->getBody(), true)["data"]["path"];
    }

    protected function getExportedTransactions(string $link): ResponseInterface
    {
        return $this->adapter->getHttpClient()
            ->get($link);
    }
}
