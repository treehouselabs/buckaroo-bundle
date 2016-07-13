<?php

namespace TreeHouse\BuckarooBundle\Tests\Report;

use TreeHouse\BuckarooBundle\Response\ResponseInterface;
use TreeHouse\BuckarooBundle\Response\SimpleSepaDirectDebitTransactionResponse;

class SimpleSepaDirectDebitTransactionResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_can_be_created()
    {
        $data = [
            'BRQ_APIRESULT' => ResponseInterface::RESULT_SUCCESS,
            'BRQ_STATUSCODE' => ResponseInterface::STATUS_SUCCESS,
            'BRQ_STATUSCODE_DETAIL' => 'C620',
            'BRQ_STATUSMESSAGE' => 'This is the status',
            'BRQ_TIMESTAMP' => '2015-01-01 12:34:56',

            'BRQ_AMOUNT' => 12.34,
            'BRQ_CONSUMERMESSAGE_CULTURE' => null,
            'BRQ_CONSUMERMESSAGE_TITLE' => 'Your SEPA Direct Debit has been scheduled.',
            'BRQ_CONSUMERMESSAGE_HTMLTEXT' => 'We have processed your request. The SEPA Direct Debit is scheduled to be collected from you bank account on Wednesday, December 16, 2015. It will be done using the mandate reference <b>0GH13</b>',
            'BRQ_CONSUMERMESSAGE_PLAINTEXT' => 'We have processed your request. The SEPA Direct Debit is scheduled to be collected from you bank account on Wednesday, December 16, 2015. It will be done using the mandate reference 0GH13',
            'BRQ_CONSUMERMESSAGE_MUSTREAD' => true,
            'BRQ_CURRENCY' => 'EUR',
            'BRQ_CUSTOMER_NAME' => 'Pietje Puk',
            'BRQ_INVOICENUMBER' => 123456789,
            'BRQ_PAYMENT' => '939E594E89AA4BB2961B012C3F46B926',
            'BRQ_PAYMENT_METHOD' => 'SimpleSepaDirectDebit',
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_COLLECTDATE' => '2015-01-01',
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_CUSTOMERIBAN' => 'Pietje Puk',
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_DIRECTDEBITTYPE' => 'First',
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_MANDATEDATE' => '2015-01-01',
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_MANDATEREFERENCE' => 'ABC123',
            'BRQ_STARTRECURRENT' => true,
            'BRQ_TRANSACTIONS' => 'ADE9AB5949924D9482E10AD1920A324D',
        ];

        $report = SimpleSepaDirectDebitTransactionResponse::create($data);

        // regular fields
        $this->assertSame($data['BRQ_APIRESULT'], $report->getApiResult());
        $this->assertSame($data['BRQ_STATUSCODE'], $report->getStatusCode());
        $this->assertSame($data['BRQ_STATUSCODE_DETAIL'], $report->getStatusCodeDetail());
        $this->assertSame($data['BRQ_STATUSMESSAGE'], $report->getStatusMessage());
        $this->assertSame($data['BRQ_TIMESTAMP'], $report->getTimestamp()->format('Y-m-d H:i:s'));
        $this->assertSame($data['BRQ_INVOICENUMBER'], $report->getInvoiceNumber());

        // specific fields
        $this->assertSame($data['BRQ_AMOUNT'], $report->getAmount()->getAmount() / 100);
        $this->assertSame($data['BRQ_CONSUMERMESSAGE_CULTURE'], $report->getConsumerMessage()->getCulture());
        $this->assertSame($data['BRQ_CONSUMERMESSAGE_TITLE'], $report->getConsumerMessage()->getTitle());
        $this->assertSame($data['BRQ_CONSUMERMESSAGE_HTMLTEXT'], $report->getConsumerMessage()->getHtmlText());
        $this->assertSame($data['BRQ_CONSUMERMESSAGE_PLAINTEXT'], $report->getConsumerMessage()->getPlainText());
        $this->assertSame($data['BRQ_CONSUMERMESSAGE_MUSTREAD'], $report->getConsumerMessage()->isMustRead());
        $this->assertSame($data['BRQ_CURRENCY'], $report->getAmount()->getCurrency()->getCode());
        $this->assertSame($data['BRQ_CUSTOMER_NAME'], $report->getCustomerName());
        $this->assertSame($data['BRQ_PAYMENT'], $report->getPayment());
        $this->assertSame($data['BRQ_PAYMENT_METHOD'], $report->getPaymentMethod());
        $this->assertSame($data['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_COLLECTDATE'], $report->getCollectDate()->format('Y-m-d'));
        $this->assertSame($data['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_CUSTOMERIBAN'], $report->getCustomerIban());
        $this->assertSame($data['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_DIRECTDEBITTYPE'], $report->getDirectDebitType());
        $this->assertSame($data['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_MANDATEDATE'], $report->getMandate()->getDate()->format('Y-m-d'));
        $this->assertSame($data['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_MANDATEREFERENCE'], $report->getMandate()->getReference());
        $this->assertSame($data['BRQ_STARTRECURRENT'], $report->isStartRecurrent());
        $this->assertSame($data['BRQ_TRANSACTIONS'], $report->getTransactions());
    }
}
