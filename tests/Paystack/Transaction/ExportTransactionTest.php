<?php

namespace Paystack\Tests\Transaction;

use Gbowo\Adapter\Paystack\PaystackAdapter;
use Gbowo\Exception\InvalidHttpResponseException;
use Paystack\Tests\Mockable;
use Paystack\Transaction\ExportTransactions;
use PHPUnit\Framework\TestCase;

class ExportTransactionTest extends TestCase
{

    use Mockable;

    public function testPluginCanBeAddedAtRuntime()
    {
        $path = "https://files.paystack.co/exports/100032/1460290758207.csv";

        $data = [
            "status" => true,
            "message" => "Export successful",
            "data" => [
                "path" => $path
            ]
        ];

        $response = $this->getMockedResponseInterface();

        $client = $this->getMockedGuzzle();

        $client->shouldReceive("get")
            ->once()
            ->andReturn($response);

        $response->shouldReceive("getStatusCode")
            ->once()
            ->andReturn(200);

        $response->shouldReceive("getBody")
            ->atMost()
            ->once()
            ->andReturn(\GuzzleHttp\json_encode($data));


        $paystack = new PaystackAdapter($client);

        $paystack->addPlugin(new ExportTransactions(PaystackAdapter::API_LINK));

        $pathToFile = $paystack->exportTransactions();

        $this->assertEquals($path, $pathToFile);
    }

    public function testAnInvalidHttpResponseIsReceived()
    {
        $this->expectException(InvalidHttpResponseException::class);
        $response = $this->getMockedResponseInterface();

        $client = $this->getMockedGuzzle();

        $client->shouldReceive("get")
            ->once()
            ->andReturn($response);

        $response->shouldReceive("getStatusCode")
            ->atMost()
            ->twice()
            ->andReturn(206);

        $response->shouldReceive("getBody")
            ->never()
            ->andReturnNull();

        $paystack = new PaystackAdapter($client);

        $paystack->addPlugin(new ExportTransactions(PaystackAdapter::API_LINK));

        $paystack->exportTransactions();
    }
}
