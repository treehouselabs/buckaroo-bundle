<?php

namespace TreeHouse\BuckarooBundle\Tests\Response;

use PHPUnit\Framework\TestCase;
use TreeHouse\BuckarooBundle\Response\IdealTransactionResponse;
use TreeHouse\BuckarooBundle\Response\ResponseInterface;

class IdealTransactionResponseTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_created()
    {
        $data = [
            'BRQ_APIRESULT' => ResponseInterface::RESULT_SUCCESS,
            'BRQ_INVOICENUMBER' => 123456789,
            'BRQ_STATUSCODE' => ResponseInterface::STATUS_FAILURE,
            'BRQ_STATUSCODE_DETAIL' => 'ABC123',
            'BRQ_STATUSMESSAGE' => 'This is the status',
            'BRQ_TIMESTAMP' => '2015-01-01 12:34:56',

            'BRQ_REDIRECTURL' => 'http://www.go-to.com/this/page/',
        ];

        $report = IdealTransactionResponse::create($data);

        $this->assertSame($data['BRQ_APIRESULT'], $report->getApiResult());
        $this->assertSame($data['BRQ_INVOICENUMBER'], $report->getInvoiceNumber());
        $this->assertSame($data['BRQ_STATUSCODE'], $report->getStatusCode());
        $this->assertSame($data['BRQ_STATUSCODE_DETAIL'], $report->getStatusCodeDetail());
        $this->assertSame($data['BRQ_STATUSMESSAGE'], $report->getStatusMessage());
        $this->assertSame($data['BRQ_TIMESTAMP'], $report->getTimestamp()->format('Y-m-d H:i:s'));

        $this->assertSame($data['BRQ_REDIRECTURL'], $report->getRedirectUrl());
    }
}
