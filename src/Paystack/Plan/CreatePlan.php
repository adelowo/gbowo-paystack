<?php

namespace Paystack\Plan;

use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;
use Gbowo\Adapter\Paystack\Traits\VerifyHttpStatusResponseCode;
use Gbowo\Plugin\AbstractPlugin;

class CreatePlan extends AbstractPlugin
{
    use VerifyHttpStatusResponseCode;

    const CREATE_PLAN_ENDPOINT = "/plan";

    protected $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getPluginAccessor(): string
    {
        return "createPlan";
    }

    public function handle(array $data)
    {
        $response = $this->adapter->getHttpClient()
            ->post($this->baseUrl . self::CREATE_PLAN_ENDPOINT, [
                "body" => json_encode($data)
            ]);

        $this->verifyResponse($response);

        return json_decode($response->getBody(), true)["data"];
    }
}
