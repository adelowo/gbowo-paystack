<?php

namespace Paystack\Tests\Customer;

use Gbowo\Exception\InvalidHttpResponseException;
use function GuzzleHttp\json_encode;
use Gbowo\Adapter\Paystack\PaystackAdapter;
use Paystack\Customer\CreateCustomer;
use Paystack\Tests\Mockable;
use PHPUnit\Framework\TestCase;

class CreateCustomerTest extends TestCase
{
    use Mockable;

    public function testPluginCanBeAddedAtRunTime()
    {

        $data = [
            "status" => true,
            "message" => "Customer created",
            "data" => [
                "email" => "bojack@horsinaround.com",
                "integration" => 100032,
                "domain" => "test",
                "customer_code" => "CUS_xnxdt6s1zg1f4nx",
                "id" => 1173,
                "createdAt" => "2016-03-29T20:03:09.584Z",
                "updatedAt" => "2016-03-29T20:03:09.584Z"
            ]
        ];;

        $response = $this->getMockedResponseInterface();

        $response->shouldReceive("getStatusCode")
            ->once()
            ->andReturn(200);

        $response->shouldReceive("getBody")
            ->atMost()
            ->once()
            ->andReturn(json_encode($data));

        $client = $this->getMockedGuzzle();

        $client->shouldReceive("post")
            ->atMost()
            ->once()
            ->andReturn($response);

        $paystack = new PaystackAdapter($client);

        $paystack->addPlugin($this->getPlugin());

        $receivedData = $paystack->createCustomer(["email" => "bojack@horsinaround.com"]);

        $this->assertEquals($data["data"], $receivedData);
    }

    public function testAnInvalidHttpResponseIsReceived()
    {
        $this->expectException(InvalidHttpResponseException::class);
        $response = $this->getMockedResponseInterface();

        $client = $this->getMockedGuzzle();

        $client->shouldReceive("post")
            ->atMost()
            ->once()
            ->andReturn($response);

        $response->shouldReceive("getStatusCode")
            ->twice()
            ->andReturn(404);

        $response->shouldReceive("getBody")
            ->never()
            ->andReturnNull();

        $paystack = new PaystackAdapter($client);

        $paystack->addPlugin($this->getPlugin());

        $receivedData = $paystack->createCustomer(["email" => "bojack@horsinaround.com"]);
    }

    protected function getPlugin() : CreateCustomer
    {
        return new CreateCustomer(PaystackAdapter::API_LINK);
    }
}
