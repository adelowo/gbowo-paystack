<?php

namespace Paystack\Tests\Customer;

use Gbowo\Adapter\Paystack\PaystackAdapter;
use Gbowo\Exception\InvalidHttpResponseException;
use Paystack\Customer\UpdateCustomer;
use Paystack\Tests\Mockable;
use PHPUnit\Framework\TestCase;

class UpdateCustomerTest extends TestCase
{
    use Mockable;

    public function testPluginIsAddedAtRunTime()
    {

        $data = [
            "status" => true,
            "message" => "Customer updated",
            "data" => [
                "integration" => 100032,
                "first_name" => "BoJack",
                "last_name" => "Horseman",
                "email" => "bojack@horsinaround.com",
                "phone" => null,
                "metadata" => [
                    "photos" => [
                        [
                            "type" => "twitter",
                            "typeId" => "twitter",
                            "typeName" => "Twitter",
                            "url" => "https://some.cdn.net/os1sks",
                            "isPrimary" => true
                        ]
                    ]
                ],
                "domain" => "test",
                "customer_code" => "CUS_xnxdt6s1zg1f4nx",
                "id" => 1173,
                "transactions" => [],
                "subscriptions" => [],
                "authorizations" => [],
                "createdAt" => "2016-03-29T20:03:09.000Z",
                "updatedAt" => "2016-03-29T20:03:10.000Z"
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

        $client->shouldReceive("put")
            ->atMost()
            ->once()
            ->andReturn($response);

        $paystack = new PaystackAdapter($client);

        $paystack->addPlugin(new UpdateCustomer(PaystackAdapter::API_LINK));

        $receivedData = $paystack->updateCustomer("CUS_xnxdt6s1zg1f4nx", ["first_name" => "BoJack"]);

        $this->assertEquals($data["data"], $receivedData);
    }

    public function testAnInvalidHttpResponseIsRecieved()
    {
        $this->expectException(InvalidHttpResponseException::class);

        $response = $this->getMockedResponseInterface();

        $client = $this->getMockedGuzzle();

        $client->shouldReceive("put")
            ->atMost()
            ->once()
            ->andReturn($response);

        $response->shouldReceive("getStatusCode")
            ->once()
            ->andReturn(204);

        $response->shouldReceive("getBody")
            ->never()
            ->andReturnNull();

        $paystack = new PaystackAdapter($client);

        $paystack->addPlugin(new UpdateCustomer(PaystackAdapter::API_LINK));

        $paystack->updateCustomer("some_link", ["first_name" => "The real clown"]);
    }
}
