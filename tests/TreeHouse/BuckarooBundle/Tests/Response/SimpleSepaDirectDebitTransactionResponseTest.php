<?php

namespace TreeHouse\BuckarooBundle\Tests\Response;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use TreeHouse\BuckarooBundle\Response\ResponseInterface;
use TreeHouse\BuckarooBundle\Response\SimpleSepaDirectDebitTransactionResponse;

class SimpleSepaDirectDebitTransactionResponseTest extends TestCase
{
    private $responseData = [
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

    /**
     * @test
     */
    public function it_can_be_created()
    {
        $report = SimpleSepaDirectDebitTransactionResponse::create($this->responseData);

        // regular fields
        $this->assertSame($this->responseData['BRQ_APIRESULT'], $report->getApiResult());
        $this->assertSame($this->responseData['BRQ_STATUSCODE'], $report->getStatusCode());
        $this->assertSame($this->responseData['BRQ_STATUSCODE_DETAIL'], $report->getStatusCodeDetail());
        $this->assertSame($this->responseData['BRQ_STATUSMESSAGE'], $report->getStatusMessage());
        $this->assertSame($this->responseData['BRQ_TIMESTAMP'], $report->getTimestamp()->format('Y-m-d H:i:s'));
        $this->assertSame($this->responseData['BRQ_INVOICENUMBER'], $report->getInvoiceNumber());

        // specific fields
        $this->assertSame($this->responseData['BRQ_AMOUNT'], $report->getAmount()->getAmount() / 100);
        $this->assertSame($this->responseData['BRQ_CONSUMERMESSAGE_CULTURE'], $report->getConsumerMessage()->getCulture());
        $this->assertSame($this->responseData['BRQ_CONSUMERMESSAGE_TITLE'], $report->getConsumerMessage()->getTitle());
        $this->assertSame($this->responseData['BRQ_CONSUMERMESSAGE_HTMLTEXT'], $report->getConsumerMessage()->getHtmlText());
        $this->assertSame($this->responseData['BRQ_CONSUMERMESSAGE_PLAINTEXT'], $report->getConsumerMessage()->getPlainText());
        $this->assertSame($this->responseData['BRQ_CONSUMERMESSAGE_MUSTREAD'], $report->getConsumerMessage()->isMustRead());
        $this->assertSame($this->responseData['BRQ_CURRENCY'], $report->getAmount()->getCurrency()->getCode());
        $this->assertSame($this->responseData['BRQ_CUSTOMER_NAME'], $report->getCustomerName());
        $this->assertSame($this->responseData['BRQ_PAYMENT'], $report->getPayment());
        $this->assertSame($this->responseData['BRQ_PAYMENT_METHOD'], $report->getPaymentMethod());
        $this->assertSame($this->responseData['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_COLLECTDATE'], $report->getCollectDate()->format('Y-m-d'));
        $this->assertSame($this->responseData['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_CUSTOMERIBAN'], $report->getCustomerIban());
        $this->assertSame($this->responseData['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_DIRECTDEBITTYPE'], $report->getDirectDebitType());
        $this->assertSame($this->responseData['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_MANDATEDATE'], $report->getMandate()->getDate()->format('Y-m-d'));
        $this->assertSame($this->responseData['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_MANDATEREFERENCE'], $report->getMandate()->getReference());
        $this->assertSame($this->responseData['BRQ_STARTRECURRENT'], $report->isStartRecurrent());
        $this->assertSame($this->responseData['BRQ_TRANSACTIONS'], $report->getTransactions());
    }

    /**
     * @test
     */
    public function it_throws_when_invalid()
    {
        $this->responseData['BRQ_STATUSCODE'] = ResponseInterface::STATUS_VALIDATION_FAILURE;
        $this->responseData['BRQ_STATUSMESSAGE'] = 'Validation failure';

        $this->expectException(RuntimeException::class);

        $this->expectExceptionMessage(sprintf(
            'The transaction with invoice number %s resulted in a %d response. The transaction cannot be completed: %s',
            $this->responseData['BRQ_INVOICENUMBER'],
            $this->responseData['BRQ_STATUSCODE'],
            $this->responseData['BRQ_STATUSMESSAGE']
        ));

        SimpleSepaDirectDebitTransactionResponse::create($this->responseData);
    }
}
