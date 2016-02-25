<?php

namespace TreeHouse\BuckarooBundle\Tests\Report;

use TreeHouse\BuckarooBundle\Report\IdealTransactionReport;
use TreeHouse\BuckarooBundle\Report\ReportInterface;
use TreeHouse\BuckarooBundle\Response\ResponseInterface;

class IdealTransactionReportTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_can_be_created()
    {
        $data = [
            'BRQ_INVOICENUMBER' => 123456789,
            'BRQ_STATUSCODE' => ResponseInterface::STATUS_FAILURE,
            'BRQ_STATUSCODE_DETAIL' => 'C620',
            'BRQ_STATUSMESSAGE' => 'This is the status',
            'BRQ_TIMESTAMP' => '2015-01-01 12:34:56',

            'BRQ_AMOUNT' => 12.34,
            'BRQ_CURRENCY' => 'EUR',
            'BRQ_CUSTOMER_NAME' => 'Pietje Puk',
            'BRQ_DESCRIPTION' => 'This is an example of an iDeal transaction report',
            'BRQ_PAYER_HASH' => 'ed930548902988eb872ce58903105cc9bd7040fa6a08dfa53bcd086cd6973398af78b969857ca0b1d2fef1132981d633135437860a0fda36305cdd14b713f92',
            'BRQ_PAYMENT' => 'B62963F863AF42CFB8B2CC22125DD110',
            'BRQ_PAYMENT_METHOD' => 'ideal',
            'BRQ_SERVICE_IDEAL_CONSUMERBIC' => 'INGNL2U',
            'BRQ_SERVICE_IDEAL_CONSUMERIBAN' => 'NL12ING0123456789',
            'BRQ_SERVICE_IDEAL_CONSUMERISSUER' => 'ING',
            'BRQ_SERVICE_IDEAL_CONSUMERNAME' => 'P. Puk',
            'BRQ_TRANSACTIONS' => 'ADE9AB5949924D9482E10AD1920A324D',
        ];

        $report = IdealTransactionReport::create($data);

        $this->assertInstanceOf(ReportInterface::class, $report);
        $this->assertSame($data['BRQ_INVOICENUMBER'], $report->getInvoiceNumber());
        $this->assertSame($data['BRQ_STATUSCODE'], $report->getStatusCode());
        $this->assertSame($data['BRQ_STATUSCODE_DETAIL'], $report->getStatusCodeDetail());
        $this->assertSame($data['BRQ_TIMESTAMP'], $report->getTimestamp()->format('Y-m-d H:i:s'));
        $this->assertSame($data['BRQ_AMOUNT'], $report->getAmount()->getAmount() / 100);
        $this->assertSame($data['BRQ_CURRENCY'], $report->getAmount()->getCurrency()->getCode());
        $this->assertSame($data['BRQ_CUSTOMER_NAME'], $report->getCustomerName());
        $this->assertSame($data['BRQ_DESCRIPTION'], $report->getDescription());
        $this->assertSame($data['BRQ_PAYER_HASH'], $report->getPayerHash());
        $this->assertSame($data['BRQ_PAYMENT'], $report->getPayment());
        $this->assertSame($data['BRQ_PAYMENT_METHOD'], $report->getPaymentMethod());
        $this->assertSame($data['BRQ_SERVICE_IDEAL_CONSUMERBIC'], $report->getConsumerBic());
        $this->assertSame($data['BRQ_SERVICE_IDEAL_CONSUMERIBAN'], $report->getConsumerIban());
        $this->assertSame($data['BRQ_SERVICE_IDEAL_CONSUMERISSUER'], $report->getConsumerIssuer());
        $this->assertSame($data['BRQ_SERVICE_IDEAL_CONSUMERNAME'], $report->getConsumerName());
        $this->assertSame($data['BRQ_TRANSACTIONS'], $report->getTransactions());
    }
}
