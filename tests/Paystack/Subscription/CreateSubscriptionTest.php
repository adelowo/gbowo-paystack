<?php

namespace Paystack\Tests\Subscription;

use Gbowo\Adapter\Paystack\PaystackAdapter;
use Gbowo\Exception\InvalidHttpResponseException;
use Paystack\Subscription\CreateSubscription;
use Paystack\Tests\Mockable;
use PHPUnit\Framework\TestCase;

class CreateSubscriptionTest extends TestCase
{

    use Mockable;

    public function testPluginIsAddedAtRuntime()
    {
        $data = [
            "status" => true,
            "message" => "Subscription successfully created",
            "data" => [
                "customer" => 1173,
                "plan" => 28,
                "integration" => 100032,
                "domain" => "test",
                "start" => 1459296064,
                "status" => "active",
                "quantity" => 1,
                "amount" => 50000,
                "authorization" => 79,
                "subscription_code" => "SUB_vsyqdmlzble3uii",
                "email_token" => "d7gofp6yppn3qz7",
                "id" => 9,
                "createdAt" => "2016-03-30T00:01:04.687Z",
                "updatedAt" => "2016-03-30T00:01:04.687Z"
            ]
        ];

        $response = $this->getMockedResponseInterface();

        $response->shouldReceive("getStatusCode")
            ->once()
            ->andReturn(201);

        $response->shouldReceive("getBody")
            ->once()
            ->andReturn(\GuzzleHttp\json_encode($data));

        $client = $this->getMockedGuzzle();

        $client->shouldReceive("post")
            ->once()
            ->andReturn($response);

        $paystack = new PaystackAdapter($client);

        $paystack->addPlugin(new CreateSubscription(PaystackAdapter::API_LINK));

        $returnedData = $paystack->createSubscription(
            "CUS_code_here",
            "PLN_gx2wn530m0i3w3m",
            "AUTH_code_here_albeit_not_needed" //refer to the api docs
        );

        $this->assertEquals($data["data"], $returnedData);

    }

    public function testAnInvalidHttpResponseIsReceived()
    {

        $this->expectException(InvalidHttpResponseException::class);

        $response = $this->getMockedResponseInterface();

        $response->shouldReceive("getStatusCode")
            ->twice()
            ->andReturn(204);

        $response->shouldReceive("getBody")
            ->never()
            ->andReturnNull();

        $client = $this->getMockedGuzzle();

        $client->shouldReceive("post")
            ->once()
            ->andReturn($response);

        $paystack = new PaystackAdapter($client);

        $paystack->addPlugin(new CreateSubscription(PaystackAdapter::API_LINK));

        $paystack->createSubscription(
            "CUS_code_here",
            "PLN_code_here"
        );
    }
}
