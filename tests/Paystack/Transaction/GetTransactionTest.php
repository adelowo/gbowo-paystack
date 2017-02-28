<?php

namespace Paystack\Tests\Transaction;

use Gbowo\Adapter\Paystack\PaystackAdapter;
use Gbowo\Exception\InvalidHttpResponseException;
use Paystack\Tests\Mockable;
use Paystack\Transaction\GetTransaction;
use PHPUnit\Framework\TestCase;

class GetTransactionTest extends TestCase
{
    use Mockable;

    public function testPluginCanBeMountedAtRunTime()
    {
        $data = [
            "status" => true,
            "message" => "Transaction retrieved",
            "data" => [
                "id" => 5833,
                "domain" => "test",
                "status" => "failed",
                "reference" => "icy9ma6jd1",
                "amount" => 100,
                "message" => null,
                "gateway_response" => "Declined",
                "paid_at" => null,
                "channel" => "card",
                "currency" => "NGN",
                "ip_address" => null,
                "metadata" => null,
                "timeline" => null,
                "customer" => [
                    "first_name" => "Ezra",
                    "last_name" => "Olubi",
                    "email" => "ezra@cfezra.com",
                    "phone" => "16504173147",
                    "metadata" => null,
                    "customer_code" => "CUS_1uld4hluw0g2gn0"
                ],
                "authorization" => [],
                "plan" => [],
            ]
        ];

        $response = $this->getMockedResponseInterface();

        $response->shouldReceive("getStatusCode")
            ->atMost()
            ->once()
            ->andReturn(200);

        $response->shouldReceive("getBody")
            ->atMost()
            ->once()
            ->andReturn(\GuzzleHttp\json_encode($data));

        $client = $this->getMockedGuzzle();

        $client->shouldReceive("get")
            ->once()
            ->andReturn($response);

        $paystack = new PaystackAdapter($client);

        $paystack->addPlugin(new GetTransaction(PaystackAdapter::API_LINK));

        $recievedData = $paystack->getTransaction("20911");

        $this->assertEquals($data["data"], $recievedData);
    }

    public function testAnInvalidHttpResponseIsReceived()
    {
        $this->expectException(InvalidHttpResponseException::class);

        $response = $this->getMockedResponseInterface();

        $response->shouldReceive("getStatusCode")
            ->atMost()
            ->twice()
            ->andReturn(206);

        $response->shouldReceive("getBody")
            ->never()
            ->andReturnNull();

        $client = $this->getMockedGuzzle();

        $client->shouldReceive("get")
            ->once()
            ->andReturn($response);

        $paystack = new PaystackAdapter($client);

        $paystack->addPlugin(new GetTransaction(PaystackAdapter::API_LINK));

        $recievedData = $paystack->getTransaction("20911");
    }
}
