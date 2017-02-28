<?php

namespace Paystack\Tests;

use Mockery;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

trait Mockable
{

    public function tearDown()
    {
        Mockery::close();
    }

    protected function getMockedGuzzle()
    {
        return Mockery::mock(Client::class)->makePartial();
    }

    protected function getMockedResponseInterface()
    {
        return Mockery::mock(ResponseInterface::class)->makePartial();
    }
}
