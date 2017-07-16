<?php

namespace Paystack\Tests\Bank;

use Exception;
use Gbowo\Adapter\Paystack\PaystackAdapter;
use Gbowo\Exception\InvalidHttpResponseException;
use Paystack\Bank\GetAccountDetails;
use Paystack\Tests\Mockable;
use PHPUnit\Framework\TestCase;

class GetAccountDetailsTest extends TestCase
{
    use Mockable;

    public function testPluginCanBeAddedAtRunTime()
    {
        $data = [
            "status" => true,
            "message" => "Banks retrieved",
            "data" => [
                "account_number" => "0022728151",
                "account_name" => "WES GIBBONS"
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

        $paystack->addPlugin(new GetAccountDetails(PaystackAdapter::API_LINK));

        $res = $paystack->getAccountDetails(["account_number" => "0022728151", "bank_code" => "063"]);

        $this->assertEquals($data["data"], $res);
    }

    public function testInvalidParams()
    {
        //This test checks if the mandatory "account_number" and "bank_code" are present in the params passed to the plugin

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

        $paystack->addPlugin(new GetAccountDetails(PaystackAdapter::API_LINK));

        $res = $paystack->getAccountDetails(["account_numbe" => "0022728151", "bank_cod" => "063"]);
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

        $paystack->addPlugin(new GetAccountDetails(PaystackAdapter::API_LINK));

        $res = $paystack->getAccountDetails(["account_number" => "0022728151", "bank_code" => "063"]);
    }
}
