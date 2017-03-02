<?php

namespace Paystack\Tests\Subscription;

use Gbowo\Adapter\Paystack\PaystackAdapter;
use Gbowo\Exception\InvalidHttpResponseException;
use Paystack\Subscription\GetAllSubscriptions;
use Paystack\Tests\Mockable;
use PHPUnit\Framework\TestCase;

class GetAllSubscriptionsTest extends TestCase
{
    use Mockable;

    public function testPluginCanBeAddedAtRuntime()
    {
        $data = $this->getSubscriptions();

        $response = $this->getMockedResponseInterface();

        $response->shouldReceive("getStatusCode")
            ->once()
            ->andReturn(200);

        $response->shouldReceive("getBody")
            ->once()
            ->andReturn(\GuzzleHttp\json_encode($data));

        $client = $this->getMockedGuzzle();

        $client->shouldReceive("get")
            ->once()
            ->andReturn($response);

        $paystack = new PaystackAdapter($client);

        $paystack->addPlugin(new GetAllSubscriptions(PaystackAdapter::API_LINK));

        //Manually remove the first two elements of the array
        //The plugin implementation actually makes use of array_splice
        array_shift($data);
        array_shift($data);

        $this->assertEquals($data, $paystack->getAllSubscriptions());
    }

    public function testAnInvalidHttpResponseIsReceived()
    {
        $this->expectException(InvalidHttpResponseException::class);

        $response = $this->getMockedResponseInterface();

        $response->shouldReceive("getStatusCode")
            ->twice()
            ->andReturn(204);

        $response->shouldReceive("getBody")
            ->never()
            ->andReturnNull();

        $client = $this->getMockedGuzzle();

        $client->shouldReceive("get")
            ->once()
            ->andReturn($response);

        $paystack = new PaystackAdapter($client);

        $paystack->addPlugin(new GetAllSubscriptions(PaystackAdapter::API_LINK));

        $paystack->getAllSubscriptions();

    }

    protected function getSubscriptions()
    {
        return [
            "status" => true,
            "message" => "Subscriptions retrieved",
            "data" => [
                [
                    "customer" => [
                        "first_name" => "BoJack",
                        "last_name" => "Horseman",
                        "email" => "bojack@horseman.com",
                        "phone" => "",
                        "metadata" => null,
                        "domain" => "test",
                        "customer_code" => "CUS_hdhye17yj8qd2tx",
                        "risk_action" => "default",
                        "id" => 84312,
                        "integration" => 100073,
                        "createdAt" => "2016-10-01T10:59:52.000Z",
                        "updatedAt" => "2016-10-01T10:59:52.000Z"
                    ],
                    "plan" => [
                        "domain" => "test",
                        "name" => "Weekly small chops",
                        "plan_code" => "PLN_0as2m9n02cl0kp6",
                        "description" => "Small chops delivered every week",
                        "amount" => 27000,
                        "interval" => "weekly",
                        "send_invoices" => true,
                        "send_sms" => true,
                        "hosted_page" => false,
                        "hosted_page_url" => null,
                        "hosted_page_summary" => null,
                        "currency" => "NGN",
                        "migrate" => null,
                        "id" => 1716,
                        "integration" => 100073,
                        "createdAt" => "2016-10-01T10:59:11.000Z",
                        "updatedAt" => "2016-10-01T10:59:11.000Z"
                    ],
                    "integration" => 123456,
                    "authorization" => 161811,
                    "domain" => "test",
                    "start" => 1475319599,
                    "status" => "active",
                    "quantity" => 1,
                    "amount" => 27000,
                    "subscription_code" => "SUB_6phdx225bavuwtb",
                    "email_token" => "ore84lyuwcv2esu",
                    "easy_cron_id" => "275226",
                    "cron_expression" => "0 0 * * 6",
                    "next_payment_date" => "2016-10-15T00:00:00.000Z",
                    "open_invoice" => "INV_qc875pkxpxuyodf",
                    "id" => 4192,
                    "createdAt" => "2016-10-01T10:59:59.000Z",
                    "updatedAt" => "2016-10-12T07:45:14.000Z"
                ]
            ],
            "meta" => [
                "total" => 1,
                "skipped" => 0,
                "perPage" => 50,
                "page" => 1,
                "pageCount" => 1
            ]
        ];
    }
}
