<?php

namespace Paystack\Customer;

use Gbowo\Plugin\AbstractPlugin;
use function GuzzleHttp\json_encode;
use function GuzzleHttp\json_decode;
use Gbowo\Exception\InvalidHttpResponseException;

class UpdateCustomer extends AbstractPlugin
{
    const UPDATE_CUSTOMER_ENDPOINT = "/customer/:identifier";

    protected $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getPluginAccessor(): string
    {
        return "updateCustomer";
    }

    public function handle(string $customerIdentifier, array $data)
    {
        $link = str_replace(":identifier", $customerIdentifier, $this->baseUrl . self::UPDATE_CUSTOMER_ENDPOINT);

        $response = $this->adapter->getHttpClient()
            ->put($link, [
                'body' => json_encode($data)
            ]);

        if ($response->getStatusCode() !== 200) {
            throw new InvalidHttpResponseException(
                "An error occurred while updating the customer details"
            );
        }

        return json_decode($response->getBody(), true)["data"];
    }
}
