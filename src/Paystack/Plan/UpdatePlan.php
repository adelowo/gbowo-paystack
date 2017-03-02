<?php

namespace Paystack\Plan;

use Gbowo\Adapter\Paystack\Traits\VerifyHttpStatusResponseCode;
use function  GuzzleHttp\json_encode;
use function GuzzleHttp\json_decode;
use Gbowo\Plugin\AbstractPlugin;

class UpdatePlan extends AbstractPlugin
{

    use VerifyHttpStatusResponseCode;

    const UPDATE_PLAN_ENDPOINT = "/plan/:identifier";

    protected $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getPluginAccessor(): string
    {
        return "updatePlan";
    }

    public function handle(string $planIdentifier, array $data = [])
    {
        $link = $this->baseUrl . str_replace(":identifier", $planIdentifier, self::UPDATE_PLAN_ENDPOINT);

        $response = $this->adapter->getHttpClient()
            ->put($link, [
                "body" => json_encode($data)
            ]);

        $this->verifyResponse($response);

        return json_decode($response->getBody(), true)["status"];
    }
}
