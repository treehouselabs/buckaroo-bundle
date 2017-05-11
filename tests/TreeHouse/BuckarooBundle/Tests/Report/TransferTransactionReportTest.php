<?php

namespace TreeHouse\BuckarooBundle\Tests\Report;

use TreeHouse\BuckarooBundle\Report\IdealTransactionReport;
use TreeHouse\BuckarooBundle\Report\ReportInterface;
use TreeHouse\BuckarooBundle\Report\TransferTransactionReport;
use TreeHouse\BuckarooBundle\Response\ResponseInterface;

class TransferTransactionReportTest extends \PHPUnit_Framework_TestCase
{
    private $amount = 12.34;
    private $currency = 'EUR';
    private $description = 'This is an example of an iDeal transaction report';
    private $invoiceNumber = 123456789;
    private $mutationType = 'Collecting';
    private $payment = 'B62963F863AF42CFB8B2CC22125DD110';
    private $test = false;
    private $timestamp = '2015-01-01 12:34:56';
    private $transactions = 'ADE9AB5949924D9482E10AD1920A324D';
    private $transactionType = 'C001';
    private $signature = '5TzIZWifLXr2gtXlYoc93dewnnY3noZWakZhtiO8';
    private $websitekey = '123456789';
    private $statusCodeDetail = 'S001';
    private $statusMessage = 'Transaction successfully processed';

    /**
     * @test
     */
    public function it_can_create_a_report()
    {
        $report = TransferTransactionReport::create(
            $this->getValidData()
        );

        $this->assertInstanceOf(ReportInterface::class, $report);
        $this->assertSame($this->amount, $report->getAmount()->getAmount() / 100);
        $this->assertSame($this->currency, $report->getAmount()->getCurrency()->getCode());
        $this->assertSame($this->description, $report->getDescription());
        $this->assertSame($this->invoiceNumber, $report->getInvoiceNumber());
        $this->assertSame($this->payment, $report->getPayment());
        $this->assertSame(ResponseInterface::STATUS_SUCCESS, $report->getStatusCode());
        $this->assertSame($this->statusCodeDetail, $report->getStatusCodeDetail());
        $this->assertSame($this->statusMessage, $report->getStatusMessage());
        $this->assertSame($this->timestamp, $report->getTimestamp()->format('Y-m-d H:i:s'));
        $this->assertNull($report->getTransactionMethod());
        $this->assertSame($this->transactionType, $report->getTransactionType());
        $this->assertSame($this->transactions, $report->getTransactions());
    }

    /**
     * @test
     */
    public function it_throws_when_its_not_a_transfer()
    {
        $this->expectException(\RuntimeException::class);

        $data = $this->getValidData(
            ResponseInterface::STATUS_SUCCESS,
            'S001',
            'Transaction successfully processed'
        );

        $data['BRQ_TRANSACTION_TYPE'] = 'Invalid';

        TransferTransactionReport::create($data);
    }

    /**
     * @return array
     */
    public function getValidData()
    {
        return [
            'BRQ_AMOUNT' => $this->amount,
            'BRQ_CURRENCY' => $this->currency,
            'BRQ_DESCRIPTION' => $this->description,
            'BRQ_INVOICENUMBER' => $this->invoiceNumber,
            'BRQ_MUTATIONTYPE' => $this->mutationType,
            'BRQ_PAYMENT' => $this->payment,
            'BRQ_STATUSCODE' => ResponseInterface::STATUS_SUCCESS,
            'BRQ_STATUSCODE_DETAIL' => $this->statusCodeDetail,
            'BRQ_STATUSMESSAGE' => $this->statusMessage,
            'BRQ_TEST' => $this->test,
            'BRQ_TIMESTAMP' => $this->timestamp,
            'BRQ_TRANSACTION_TYPE' => $this->transactionType,
            'BRQ_TRANSACTIONS' => $this->transactions,
            'BRQ_WEBSITEKEY' => $this->websitekey,
            'BRQ_SIGNATURE' => $this->signature
        ];
    }
}
