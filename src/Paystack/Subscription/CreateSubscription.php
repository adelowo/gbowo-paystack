<?php

namespace Paystack\Subscription;

use Gbowo\Adapter\Paystack\Traits\VerifyHttpStatusResponseCode;
use function GuzzleHttp\json_encode;
use function GuzzleHttp\json_decode;
use Gbowo\Plugin\AbstractPlugin;

class CreateSubscription extends AbstractPlugin
{
    use VerifyHttpStatusResponseCode;

    const CREATE_SUBSCRIPTION_ENDPOINT = "/subscription";

    protected $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getPluginAccessor(): string
    {
        return "createSubscription";
    }

    public function handle(
        string $customerIdentifier,
        string $planCode,
        string $customerAuth = ""
    ) {

        $response = $this->adapter->getHttpClient()
            ->post($this->baseUrl . self::CREATE_SUBSCRIPTION_ENDPOINT, [
                "body" => json_encode($this->makeBodyParams($customerIdentifier, $planCode, $customerAuth))
            ]);

        $this->verifyResponse($response);

        return json_decode($response->getBody(), true)["data"];
    }

    protected function makeBodyParams(
        string $customerIdentifier,
        string $planCode,
        string $customerAuth
    ): array {
        $bodyParams = ["customer" => $customerIdentifier, "plan" => $planCode];

        if ($customerAuth) {
            $bodyParams["authorization"] = $customerAuth;
        }

        return $bodyParams;
    }
}
