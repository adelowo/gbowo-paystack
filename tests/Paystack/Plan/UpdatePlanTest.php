<?php

namespace Paystack\Tests\Plan;

use Gbowo\Adapter\Paystack\PaystackAdapter;
use Gbowo\Exception\InvalidHttpResponseException;
use Paystack\Plan\UpdatePlan;
use Paystack\Tests\Mockable;
use PHPUnit\Framework\TestCase;

class UpdatePlanTest extends TestCase
{
    use Mockable;

    public function testPluginCanBeAddedAtRuntime()
    {

        $data = [
            "status" => true,
            "message" => "Plan updated"
        ];

        $response = $this->getMockedResponseInterface();

        $response->shouldReceive("getStatusCode")
            ->once()
            ->andReturn(200);

        $response->shouldReceive("getBody")
            ->once()
            ->andReturn(\GuzzleHttp\json_encode($data));

        $client = $this->getMockedGuzzle();

        $client->shouldReceive("put")
            ->once()
            ->andReturn($response);

        $paystack = new PaystackAdapter($client);

        $paystack->addPlugin(new UpdatePlan(PaystackAdapter::API_LINK));

        $status = $paystack->updatePlan(
            "PLN_gx2wn530m0i3w3m",
            ["name" => "New name", "amount" => \Gbowo\toKobo(200000)]
        );

        $this->assertTrue($status);
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

        $client->shouldReceive("put")
            ->once()
            ->andReturn($response);

        $paystack = new PaystackAdapter($client);

        $paystack->addPlugin(new UpdatePlan(PaystackAdapter::API_LINK));

        $paystack->updatePlan("PLN_gx2wn530m0i3w3m", ["name" => "renamed yet again"]);
    }
}
