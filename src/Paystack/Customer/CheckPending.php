<?php

namespace Paystack\Customer;

use Gbowo\Adapter\Paystack\Traits\VerifyHttpStatusResponseCode;
use Gbowo\Plugin\AbstractPlugin;
use function GuzzleHttp\json_encode;
use function GuzzleHttp\json_decode;

class CheckPending extends AbstractPlugin
{
    use VerifyHttpStatusResponseCode;

    const GET_PENDING_CHARGE_ENDPOINT = "/charge/:ref";

    protected $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getPluginAccessor(): string
    {
        return "checkPending";
    }

    public function handle(string $reference)
    {
        $response = $this->adapter->getHttpClient()
            ->get($this->baseUrl . str_replace(":ref", $reference, self::GET_PENDING_CHARGE_ENDPOINT));

        $this->verifyResponse($response);

        return json_decode($response->getBody(), true)["data"];
    }
}
