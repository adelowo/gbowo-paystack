<?php

namespace Paystack\Tests\Bank;

use Exception;
use Gbowo\Adapter\Paystack\PaystackAdapter;
use Gbowo\Exception\InvalidHttpResponseException;
use Paystack\Bank\GetBVN;
use Paystack\Tests\Mockable;
use PHPUnit\Framework\TestCase;

class GetBVNTest extends TestCase
{
    use Mockable;

    public function testPluginCanBeAddedAtRunTime()
    {
        $data = [
            "status" => true,
            "message" => "Banks retrieved",
            "data" => [
                 "first_name" => "WES",
                "last_name" => "GIBSONS",
                "dob" => "14-OCT-72",
                "mobile" => "xxx-xxx-xxx",
                "bvn" => "12345678901"
            ],
            "meta" => [
                "calls_this_month" => 1,
                "free_calls_left" => 9
            ]
        ];

        $response = $this->getMockedResponseInterface();

        $response->shouldReceive("getStatusCode")
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

        $paystack->addPlugin(new GetBVN(PaystackAdapter::API_LINK));

        $res = $paystack->getBVN(12345678901);

        //Make sure we get only the data and meta keys
        unset($data["status"]);
        unset($data["message"]);

        $this->assertEquals($data, $res);
    }

    public function testBVNMustBeElevenDigits()
    {
        $this->expectException(Exception::class);

        $response = $this->getMockedResponseInterface();

        $response->shouldReceive("getStatusCode")
            ->never()
            ->andReturn(204);

        $response->shouldReceive("getBody")
            ->never()
            ->andReturnNull();

        $client = $this->getMockedGuzzle();

        $client->shouldReceive("get")
            ->never()
            ->andReturn($response);

        $paystack = new PaystackAdapter($client);

        $paystack->addPlugin(new GetBVN(PaystackAdapter::API_LINK));

        $res = $paystack->getBVN("1234567890");
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

        $client->shouldReceive("get")
            ->once()
            ->andReturn($response);

        $paystack = new PaystackAdapter($client);

        $paystack->addPlugin(new GetBVN(PaystackAdapter::API_LINK));

        $res = $paystack->getBVN("12345678901");
    }
}
