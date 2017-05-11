<?php

namespace TreeHouse\BuckarooBundle\Tests\Report;

use TreeHouse\BuckarooBundle\Report\ReportInterface;
use TreeHouse\BuckarooBundle\Report\SimpleSepaDirectDebitTransactionDebitReport;
use TreeHouse\BuckarooBundle\Response\ResponseInterface;

class SimpleSepaDirectDebitTransactionDebitReportTest extends \PHPUnit_Framework_TestCase
{
    private $amount = 12.34;
    private $currency = 'EUR';
    private $customerName = 'Pietje Puk';
    private $invoiceNumber = 123456789;
    private $payment = 'B62963F863AF42CFB8B2CC22125DD110';
    private $customerIban = 'NL12ING0123456789';
    private $timestamp = '2015-01-01 12:34:56';
    private $collectDate = '2015-01-02 12:34:56';
    private $mandateDate = '2015-01-03 12:34:56';
    private $mandateReference = 'ABC1234';
    private $transactions = 'ADE9AB5949924D9482E10AD1920A324D';
    private $startRecurrent = true;
    private $directDebitType = 'Recurring';
    private $transactionType = 'C008';
    private $transactionMethod = 'SimpleSepaDirectDebit';

    /**
     * @test
     */
    public function it_can_create_a_report()
    {
        $statuscode = ResponseInterface::STATUS_SUCCESS;
        $statusMessage = 'Transaction successfully processed';

        $data = [
            'BRQ_INVOICENUMBER' => $this->invoiceNumber,
            'BRQ_STATUSCODE' => $statuscode,
            'BRQ_STATUSMESSAGE' => $statusMessage,
            'BRQ_TIMESTAMP' => $this->timestamp,
            'BRQ_AMOUNT' => $this->amount,
            'BRQ_CURRENCY' => $this->currency,
            'BRQ_CUSTOMER_NAME' => $this->customerName,
            'BRQ_PAYMENT' => $this->payment,
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_COLLECTDATE' => $this->collectDate,
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_CUSTOMERIBAN' => $this->customerIban,
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_DIRECTDEBITTYPE' => $this->directDebitType,
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_MANDATEDATE' => $this->mandateDate,
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_MANDATEREFERENCE' => $this->mandateReference,
            'BRQ_STARTRECURRENT' => $this->startRecurrent,
            'BRQ_TRANSACTION_TYPE' => $this->transactionType,
            'BRQ_TRANSACTIONS' => $this->transactions,
        ];

        $report = SimpleSepaDirectDebitTransactionDebitReport::create($data);

        $this->assertInstanceOf(ReportInterface::class, $report);
        $this->assertSame($this->amount, $report->getAmount()->getAmount() / 100);
        $this->assertSame($this->currency, $report->getAmount()->getCurrency()->getCode());
        $this->assertSame($this->customerName, $report->getCustomerName());
        $this->assertSame($this->invoiceNumber, $report->getInvoiceNumber());
        $this->assertSame($this->payment, $report->getPayment());
        $this->assertSame($this->collectDate, $report->getCollectDate()->format('Y-m-d H:i:s'));
        $this->assertSame($this->customerIban, $report->getCustomerIban());
        $this->assertSame($this->directDebitType, $report->getDirectDebitType());
        $this->assertSame($this->mandateDate, $report->getMandateDate()->format('Y-m-d H:i:s'));
        $this->assertSame($this->mandateReference, $report->getMandateReference());
        $this->assertSame($statuscode, $report->getStatusCode());
        $this->assertSame($statusMessage, $report->getStatusMessage());
        $this->assertSame($this->timestamp, $report->getTimestamp()->format('Y-m-d H:i:s'));
        $this->assertSame($this->transactions, $report->getTransactions());
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function it_fails_when_creating_a_credit_report()
    {
        $statuscode = ResponseInterface::STATUS_SUCCESS;
        $statusMessage = 'Transaction successfully processed';

        $data = [
            'BRQ_INVOICENUMBER' => $this->invoiceNumber,
            'BRQ_STATUSCODE' => $statuscode,
            'BRQ_STATUSMESSAGE' => $statusMessage,
            'BRQ_TIMESTAMP' => $this->timestamp,
            'BRQ_AMOUNT' => $this->amount,
            'BRQ_CURRENCY' => $this->currency,
            'BRQ_CUSTOMER_NAME' => $this->customerName,
            'BRQ_PAYMENT' => $this->payment,
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_COLLECTDATE' => $this->collectDate,
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_CUSTOMERIBAN' => $this->customerIban,
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_DIRECTDEBITTYPE' => $this->directDebitType,
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_MANDATEDATE' => $this->mandateDate,
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_MANDATEREFERENCE' => $this->mandateReference,
            'BRQ_STARTRECURRENT' => $this->startRecurrent,
            'BRQ_TRANSACTION_TYPE' => 'C005',
            'BRQ_TRANSACTIONS' => $this->transactions,
        ];

        SimpleSepaDirectDebitTransactionDebitReport::create($data);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_fails_when_data_is_missing()
    {
        $statuscode = ResponseInterface::STATUS_SUCCESS;
        $statusMessage = 'Transaction successfully processed';

        $data = [
            'BRQ_INVOICENUMBER' => $this->invoiceNumber,
            'BRQ_STATUSCODE' => $statuscode,
            'BRQ_STATUSMESSAGE' => $statusMessage,
            'BRQ_TIMESTAMP' => $this->timestamp,
            'BRQ_AMOUNT' => $this->amount,
            'BRQ_CURRENCY' => $this->currency,
            'BRQ_TRANSACTION_TYPE' => $this->transactionType,
            'BRQ_TRANSACTIONS' => $this->transactions,
        ];

        SimpleSepaDirectDebitTransactionDebitReport::create($data);
    }
}
