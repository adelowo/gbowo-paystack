<?php

namespace Paystack\Transaction;

use Gbowo\Adapter\Paystack\Traits\VerifyHttpStatusResponseCode;
use GuzzleHttp\json_decode;
use Gbowo\Plugin\AbstractPlugin;
use Psr\Http\Message\ResponseInterface;

class ExportTransactions extends AbstractPlugin
{

    use VerifyHttpStatusResponseCode;

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

        $this->verifyResponse($response);

        return json_decode($response->getBody(), true)["data"]["path"];
    }

    protected function getExportedTransactions(string $link): ResponseInterface
    {
        return $this->adapter->getHttpClient()
            ->get($link);
    }
}
