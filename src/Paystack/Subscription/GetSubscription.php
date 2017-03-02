<?php

namespace Paystack\Subscription;

use function GuzzleHttp\json_decode;
use Gbowo\Adapter\Paystack\Traits\VerifyHttpStatusResponseCode;
use Gbowo\Plugin\AbstractPlugin;

class GetSubscription extends AbstractPlugin
{

    use VerifyHttpStatusResponseCode;

    const GET_SUBSCRIPTION_ENDPOINT = "/subscription/:identifier";

    protected $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getPluginAccessor(): string
    {
        return "getSubscription";
    }

    public function handle(string $subscriptionIdentifier)
    {
        $link = $this->baseUrl . str_replace(":identifier", $subscriptionIdentifier, self::GET_SUBSCRIPTION_ENDPOINT);

        $response = $this->adapter->getHttpClient()
            ->get($link);

        $this->verifyResponse($response);

        return json_decode($response->getBody(), true)["data"];
    }
}
