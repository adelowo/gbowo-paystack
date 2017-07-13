<?php

namespace Paystack\Tests\Subscription;

use Gbowo\Adapter\Paystack\PaystackAdapter;
use Gbowo\Exception\InvalidHttpResponseException;
use Paystack\Subscription\GetSubscription;
use Paystack\Tests\Mockable;
use PHPUnit\Framework\TestCase;

class GetSubscriptionTest extends TestCase
{

    use Mockable;

    public function testPluginCanBeAddeAtRuntime()
    {
        $data = $this->getResponse();

        $response = $this->getMockedResponseInterface();

        $response->shouldReceive("getStatusCode")
            ->once()
            ->andReturn(201);

        $response->shouldReceive("getBody")
            ->once()
            ->andReturn(\GuzzleHttp\json_encode($data));

        $client = $this->getMockedGuzzle();

        $client->shouldReceive("get")
            ->once()
            ->andReturn($response);

        $paystack = new PaystackAdapter($client);

        $paystack->addPlugin(new GetSubscription(PaystackAdapter::API_LINK));

        $this->assertEquals($data["data"], $paystack->getSubscription("SUB_vsyqdmlzble3uii"));
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

        $paystack->addPlugin(new GetSubscription(PaystackAdapter::API_LINK));

        $paystack->getSubscription("SUB_vsyqdmlzble3uii");
    }
    
    protected function getResponse()
    {
        return [
            "status" => true,
            "message" => "Subscription retrieved successfully",
            "data" => [
                "invoices" => [],
                "customer" => [
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
                                "url" => "https://d2ojpxxtu63wzl.cloudfront.net/static/61b1a0a1d4dda2c9fe9e165fed07f812_a722ae7148870cc2e33465d1807dfdc6efca33ad2c4e1f8943a79eead3c21311",
                                "isPrimary" => false
                            ]
                        ]
                    ],
                    "domain" => "test",
                    "customer_code" => "CUS_xnxdt6s1zg1f4nx",
                    "id" => 1173,
                    "integration" => 100032,
                    "createdAt" => "2016-03-29T20:03:09.000Z",
                    "updatedAt" => "2016-03-29T20:53:05.000Z"
                ],
                "plan" => [
                    "domain" => "test",
                    "name" => "Monthly retainer (renamed)",
                    "plan_code" => "PLN_gx2wn530m0i3w3m",
                    "description" => null,
                    "amount" => 50000,
                    "interval" => "monthly",
                    "send_invoices" => true,
                    "send_sms" => true,
                    "hosted_page" => false,
                    "hosted_page_url" => null,
                    "hosted_page_summary" => null,
                    "currency" => "NGN",
                    "id" => 28,
                    "integration" => 100032,
                    "createdAt" => "2016-03-29T22:42:50.000Z",
                    "updatedAt" => "2016-03-29T23:51:41.000Z"
                ],
                "integration" => 100032,
                "authorization" => [
                    "domain" => "test",
                    "authorization_code" => "AUTH_5u1q4898",
                    "bin" => null,
                    "brand" => null,
                    "card_type" => "Visa",
                    "last4" => "1381",
                    "bank" => null,
                    "country_code" => null,
                    "country_name" => null,
                    "description" => "Visa ending with 1381",
                    "mobile" => false,
                    "id" => 79,
                    "integration" => 100032,
                    "customer" => 1173,
                    "card" => 392,
                    "createdAt" => "2016-01-13T01:15:52.000Z",
                    "updatedAt" => "2016-01-13T01:15:52.000Z"
                ],
                "domain" => "test",
                "start" => 1459296064,
                "status" => "active",
                "quantity" => 1,
                "amount" => 50000,
                "subscription_code" => "SUB_vsyqdmlzble3uii",
                "email_token" => "d7gofp6yppn3qz7",
                "easy_cron_id" => null,
                "cron_expression" => "0 0 28 * *",
                "next_payment_date" => "2016-04-28T07:00:00.000Z",
                "open_invoice" => null,
                "id" => 9,
                "createdAt" => "2016-03-30T00:01:04.000Z",
                "updatedAt" => "2016-03-30T00:22:58.000Z"
            ]
        ];
    }
}
