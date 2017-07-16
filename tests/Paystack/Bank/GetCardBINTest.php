<?php

namespace Paystack\Tests\Bank;

use Gbowo\Adapter\Paystack\PaystackAdapter;
use Gbowo\Exception\InvalidHttpResponseException;
use Paystack\Bank\GetCardBIN;
use Paystack\Tests\Mockable;
use PHPUnit\Framework\TestCase;

class GetCardBINTest extends TestCase
{
    use Mockable;

    public function testPluginCanBeAddedAtRunTime()
    {
        $data = [
            "status" => true,
            "message" => "Bin resolved",
            "data" => [
                "bin" => "539983",
                "brand" => "Mastercard",
                "sub_brand" => "",
                "country_name" => "Nigeria",
                "country_code" => "NG",
                "card_type" => "DEBIT",
                "bank" => "Guaranty Trust Bank",
                "linked_bank_id" => 9
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
            ->with(PaystackAdapter::API_LINK.str_replace(":bin", "539983", GetCardBIN::RESOLVE_CARD_BIN_ENDPOINT))
            ->andReturn($response);

        $paystack = new PaystackAdapter($client);

        $paystack->addPlugin(new GetCardBIN(PaystackAdapter::API_LINK));

        $res = $paystack->getCardBIN("539983");

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

        $paystack->addPlugin(new GetCardBIN(PaystackAdapter::API_LINK));

        $res = $paystack->getCardBIN("539983");
    }
}
