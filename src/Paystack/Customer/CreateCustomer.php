<?php

namespace Paystack\Customer;

use Gbowo\Plugin\AbstractPlugin;
use function GuzzleHttp\json_encode;
use function GuzzleHttp\json_decode;
use Gbowo\Exception\InvalidHttpResponseException;

class CreateCustomer extends AbstractPlugin
{
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

        if ($response->getStatusCode() !== 200) {
            throw new InvalidHttpResponseException(
                "An error occurred while processing this request. Expected 200 response status code, got {$response->getStatusCode()} instead"
            );
        }

        return json_decode($response->getBody(), true)["data"];
    }
}
