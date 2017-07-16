<?php

namespace Paystack\Tests\Bank;

use Gbowo\Adapter\Paystack\PaystackAdapter;
use Gbowo\Exception\InvalidHttpResponseException;
use Paystack\Bank\ListBanks;
use Paystack\Tests\Mockable;
use PHPUnit\Framework\TestCase;

class ListBanksTest extends TestCase
{
    use Mockable;

    public function testPluginCanBeAddedAtRunTime()
    {
        $data = [
            "status" => true,
            "message" => "Banks retrieved",
            "data" => [
            [
                "name" => "Access Bank",
                "slug" => "access-bank",
                "code" => "044",
                "longcode" => "044150149",
                "gateway" => "etz",
                "active" => true,
                "id" => 1
            ],
            [

                "name" => "Citibank Nigeria",
                "slug" => "citibank-nigeria",
                "code" => "023",
                "longcode" => "023150005",
                "gateway" => "",
                "active" => true
            ]
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

        $paystack->addPlugin(new ListBanks(PaystackAdapter::API_LINK));

        $res = $paystack->listBanks();

        $this->assertEquals($data["data"], $res);
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

        $paystack->addPlugin(new ListBanks(PaystackAdapter::API_LINK));

        $res = $paystack->listBanks();
    }
}
