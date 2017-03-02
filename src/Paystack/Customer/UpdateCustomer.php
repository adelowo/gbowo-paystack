<?php

namespace Paystack\Customer;

use Gbowo\Adapter\Paystack\Traits\VerifyHttpStatusResponseCode;
use Gbowo\Plugin\AbstractPlugin;
use function GuzzleHttp\json_encode;
use function GuzzleHttp\json_decode;

class UpdateCustomer extends AbstractPlugin
{
    use VerifyHttpStatusResponseCode;

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

    public function handle(string $customerIdentifier, array $data = [])
    {
        $link = str_replace(":identifier", $customerIdentifier, $this->baseUrl . self::UPDATE_CUSTOMER_ENDPOINT);

        $response = $this->adapter->getHttpClient()
            ->put($link, [
                'body' => json_encode($data)
            ]);

        $this->verifyResponse($response);

        return json_decode($response->getBody(), true)["data"];
    }
}
