<?php

namespace Paystack\Customer;

use Gbowo\Adapter\Paystack\Traits\VerifyHttpStatusResponseCode;
use Gbowo\Plugin\AbstractPlugin;
use function GuzzleHttp\json_encode;
use function GuzzleHttp\json_decode;

class CreateCustomer extends AbstractPlugin
{
    use VerifyHttpStatusResponseCode;

    const CREATE_CUSTOMER_ENDPOINT = "/customer";

    protected $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getPluginAccessor(): string
    {
        return "createCustomer";
    }

    public function handle(array $data)
    {
        $response = $this->adapter->getHttpClient()
            ->post($this->baseUrl . self::CREATE_CUSTOMER_ENDPOINT, [
                'body' => json_encode($data)
            ]);

        $this->verifyResponse($response);

        return json_decode($response->getBody(), true)["data"];
    }
}
