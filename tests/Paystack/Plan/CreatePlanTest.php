<?php

namespace Paystack\Tests\Plan;

use Gbowo\Adapter\Paystack\PaystackAdapter;
use Gbowo\Exception\InvalidHttpResponseException;
use Paystack\Plan\CreatePlan;
use Paystack\Tests\Mockable;
use PHPUnit\Framework\TestCase;

class CreatePlanTest extends TestCase
{
    use Mockable;

    public function testPluginCanBeAddedAtRuntime()
    {
        $amount = \Gbowo\toKobo(1570000);

        $data = [
            "status" => true,
            "message" => "Plan created",
            "data" => [
                "name" => "Some rich kidz club",
                "amount" => $amount,
                "interval" => "weekly",
                "integration" => 100032,
                "domain" => "test",
                "plan_code" => "PLN_gx2wn530m0i3w3m",
                "send_invoices" => true,
                "send_sms" => true,
                "hosted_page" => false,
                "currency" => "NGN",
                "id" => 28,
                "createdAt" => "2017-03-02T22:42:50.811Z",
                "updatedAt" => "2017-03-02T22:42:50.811Z"
            ]
        ];

        $response = $this->getMockedResponseInterface();

        $response->shouldReceive("getStatusCode")
            ->once()
            ->andReturn(200);

        $response->shouldReceive("getBody")
            ->once()
            ->andReturn(\GuzzleHttp\json_encode($data));

        $client = $this->getMockedGuzzle();

        $client->shouldReceive("post")
            ->once()
            ->andReturn($response);


        $paystack = new PaystackAdapter($client);

        $paystack->addPlugin(new CreatePlan(PaystackAdapter::API_LINK));

        $returnedData = $paystack->createPlan($this->getPlanData($amount));

        $this->assertEquals($data["data"], $returnedData);
    }

    public function testAnInvalidHttpResponseIsReceived()
    {
        $this->expectException(InvalidHttpResponseException::class);

        $response = $this->getMockedResponseInterface();

        $response->shouldReceive("getStatusCode")
            ->once()
            ->andReturn(204);

        $response->shouldReceive("getBody")
            ->never()
            ->andReturnNull();

        $client = $this->getMockedGuzzle();

        $client->shouldReceive("post")
            ->once()
            ->andReturn($response);

        $paystack = new PaystackAdapter($client);

        $paystack->addPlugin(new CreatePlan(PaystackAdapter::API_LINK));

        $paystack->createPlan($this->getPlanData(\Gbowo\toKobo(920383763)));

    }

    protected function getPlanData($amount): array
    {
        return ["name" => "Some rich kidz club", "amount" => $amount, "interval" => "weekly"];
    }
}
