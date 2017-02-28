<?php

namespace Paystack\Tests\Customer;

use Gbowo\Adapter\Paystack\PaystackAdapter;
use Gbowo\Exception\InvalidHttpResponseException;
use Paystack\Customer\DeactivateAuthorization;
use Paystack\Tests\Mockable;
use PHPUnit\Framework\TestCase;

class DeactivateAuthorizationTest extends TestCase
{
    use Mockable;

    public function testPluginCanBeAddedAtRunTime()
    {

        $data = [
            "status" => true,
            "message" => "Authorization has been deactivated"
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

        $client->shouldReceive("post")
            ->atMost()
            ->once()
            ->andReturn($response);

        $paystack = new PaystackAdapter($client);

        $paystack->addPlugin(new DeactivateAuthorization(PaystackAdapter::API_LINK));

        $this->assertTrue($paystack->deactivateAuthorization("AUTH_au6hc0de"));
    }

    public function testAnInvalidHttpResponseIsReceived()
    {
        $response = $this->getMockedResponseInterface();

        $response->shouldReceive("getStatusCode")
            ->twice()
            ->andReturn(203);

        $response->shouldReceive("getBody")
            ->never()
            ->andReturnNull();

        $client = $this->getMockedGuzzle();

        $client->shouldReceive("post")
            ->once()
            ->andReturn($response);

        $this->expectException(InvalidHttpResponseException::class);
        $paystack = new PaystackAdapter($client);

        $paystack->addPlugin(new DeactivateAuthorization(PaystackAdapter::API_LINK));

        $paystack->deactivateAuthorization("auth_token_code");
    }
}
