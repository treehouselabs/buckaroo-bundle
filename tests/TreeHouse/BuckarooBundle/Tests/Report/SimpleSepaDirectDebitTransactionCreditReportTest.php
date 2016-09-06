<?php

namespace TreeHouse\BuckarooBundle\Tests\Report;

use TreeHouse\BuckarooBundle\Report\ReportInterface;
use TreeHouse\BuckarooBundle\Report\SimpleSepaDirectDebitTransactionCreditReport;
use TreeHouse\BuckarooBundle\Response\ResponseInterface;

class SimpleSepaDirectDebitTransactionCreditReportTest extends \PHPUnit_Framework_TestCase
{
    private $amount = 12.34;
    private $currency = 'EUR';
    private $customerName = 'Pietje Puk';
    private $invoiceNumber = 123456789;
    private $payment = 'B62963F863AF42CFB8B2CC22125DD110';
    private $timestamp = '2015-01-01 12:34:56';
    private $transactions = 'ADE9AB5949924D9482E10AD1920A324D';
    private $transactionType = 'C005';
    private $transactionMethod = 'SimpleSepaDirectDebit';
    private $reasonCode = 'MD06';
    private $reasonExplanation = 'Debtor+uses+8+weeks+reversal+right';

    /**
     * @test
     */
    public function it_can_create_a_report()
    {
        $statuscode = ResponseInterface::STATUS_SUCCESS;
        $statusMessage = 'Transaction successfully processed';

        $data = [
            'BRQ_AMOUNT_CREDIT' => $this->amount,
            'BRQ_CURRENCY' => $this->currency,
            'BRQ_CUSTOMER_NAME' => $this->customerName,
            'BRQ_INVOICENUMBER' => $this->invoiceNumber,
            'BRQ_PAYMENT' => $this->payment,
            'BRQ_TRANSACTION_METHOD' => $this->transactionMethod,
            'BRQ_TRANSACTION_TYPE' => $this->transactionType,
            'BRQ_STATUSCODE' => $statuscode,
            'BRQ_STATUSMESSAGE' => $statusMessage,
            'BRQ_TIMESTAMP' => $this->timestamp,
            'BRQ_TRANSACTIONS' => $this->transactions,
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_REASONCODE' => $this->reasonCode,
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_REASONEXPLANATION' => $this->reasonExplanation,
        ];

        $report = SimpleSepaDirectDebitTransactionCreditReport::create($data);

        $this->assertInstanceOf(ReportInterface::class, $report);
        $this->assertSame($this->amount, $report->getAmount()->getAmount() / 100);
        $this->assertSame($this->currency, $report->getAmount()->getCurrency()->getCode());
        $this->assertSame($this->customerName, $report->getCustomerName());
        $this->assertSame($this->invoiceNumber, $report->getInvoiceNumber());
        $this->assertSame($this->payment, $report->getPayment());
        $this->assertSame($this->transactionMethod, $report->getTransactionMethod());
        $this->assertSame($this->transactionType, $report->getTransactionType());
        $this->assertSame($statuscode, $report->getStatusCode());
        $this->assertSame($statusMessage, $report->getStatusMessage());
        $this->assertSame($this->timestamp, $report->getTimestamp()->format('Y-m-d H:i:s'));
        $this->assertSame($this->transactions, $report->getTransactions());
        $this->assertSame($this->reasonCode, $report->getReasonCode());
        $this->assertSame($this->reasonExplanation, $report->getReasonExplanation());
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function it_fails_when_creating_a_debit_report()
    {
        $statuscode = ResponseInterface::STATUS_SUCCESS;
        $statusMessage = 'Transaction successfully processed';

        $data = [
            'BRQ_AMOUNT_CREDIT' => $this->amount,
            'BRQ_CURRENCY' => $this->currency,
            'BRQ_CUSTOMER_NAME' => $this->customerName,
            'BRQ_INVOICENUMBER' => $this->invoiceNumber,
            'BRQ_PAYMENT' => $this->payment,
            'BRQ_TRANSACTION_METHOD' => $this->transactionMethod,
            'BRQ_TRANSACTION_TYPE' => 'C008',
            'BRQ_STATUSCODE' => $statuscode,
            'BRQ_STATUSMESSAGE' => $statusMessage,
            'BRQ_TIMESTAMP' => $this->timestamp,
            'BRQ_TRANSACTIONS' => $this->transactions,
        ];

        SimpleSepaDirectDebitTransactionCreditReport::create($data);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_fails_when_data_is_missing()
    {
        $data = [
            'BRQ_AMOUNT_CREDIT' => $this->amount,
            'BRQ_CURRENCY' => $this->currency,
            'BRQ_CUSTOMER_NAME' => $this->customerName,
            'BRQ_INVOICENUMBER' => $this->invoiceNumber,
            'BRQ_PAYMENT' => $this->payment,
            'BRQ_TRANSACTION_METHOD' => $this->transactionMethod,
            'BRQ_TRANSACTION_TYPE' => $this->transactionType,
            'BRQ_TRANSACTIONS' => $this->transactions,
        ];

        SimpleSepaDirectDebitTransactionCreditReport::create($data);
    }
}
