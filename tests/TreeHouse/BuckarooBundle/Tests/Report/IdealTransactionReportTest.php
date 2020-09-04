<?php

namespace TreeHouse\BuckarooBundle\Tests\Report;

use PHPUnit\Framework\TestCase;
use TreeHouse\BuckarooBundle\Report\IdealTransactionReport;
use TreeHouse\BuckarooBundle\Report\ReportInterface;
use TreeHouse\BuckarooBundle\Response\ResponseInterface;

class IdealTransactionReportTest extends TestCase
{
    private $amount = 12.34;
    private $currency = 'EUR';
    private $customerName = 'Pietje Puk';
    private $description = 'This is an example of an iDeal transaction report';
    private $invoiceNumber = 123456789;
    private $payerHash = 'ed930548902988eb872ce58903105cc9bd7040fa6a08dfa53bcd086cd6973398af78b969857ca0b1d2fef1132981d633135437860a0fda36305cdd14b713f92';
    private $payment = 'B62963F863AF42CFB8B2CC22125DD110';
    private $paymentMethod = 'ideal';
    private $consumerBic = 'INGNL2U';
    private $consumerIban = 'NL12ING0123456789';
    private $consumerIssuer = 'ING';
    private $consumerName = 'P. Puk';
    private $timestamp = '2015-01-01 12:34:56';
    private $transactions = 'ADE9AB5949924D9482E10AD1920A324D';
    private $signature = '5TzIZWifLXr2gtXlYoc93dewnnY3noZWakZhtiO8';

    /**
     * @test
     */
    public function it_can_create_a_success_report()
    {
        $statuscode = ResponseInterface::STATUS_SUCCESS;
        $statuscodeDetail = 'S001';
        $statusMessage = 'Transaction successfully processed';

        $data = [
            'BRQ_AMOUNT' => $this->amount,
            'BRQ_CURRENCY' => $this->currency,
            'BRQ_CUSTOMER_NAME' => $this->customerName,
            'BRQ_DESCRIPTION' => $this->description,
            'BRQ_INVOICENUMBER' => $this->invoiceNumber,
            'BRQ_PAYER_HASH' => $this->payerHash,
            'BRQ_PAYMENT' => $this->payment,
            'BRQ_PAYMENT_METHOD' => $this->paymentMethod,
            'BRQ_SERVICE_IDEAL_CONSUMERBIC' => $this->consumerBic,
            'BRQ_SERVICE_IDEAL_CONSUMERIBAN' => $this->consumerIban,
            'BRQ_SERVICE_IDEAL_CONSUMERISSUER' => $this->consumerIssuer,
            'BRQ_SERVICE_IDEAL_CONSUMERNAME' => $this->consumerName,
            'BRQ_STATUSCODE' => $statuscode,
            'BRQ_STATUSCODE_DETAIL' => $statuscodeDetail,
            'BRQ_STATUSMESSAGE' => $statusMessage,
            'BRQ_TIMESTAMP' => $this->timestamp,
            'BRQ_TRANSACTIONS' => $this->transactions,
            'BRQ_SIGNATURE' => $this->signature,

        ];

        $report = IdealTransactionReport::create($data);

        $this->assertInstanceOf(ReportInterface::class, $report);
        $this->assertSame($this->amount, $report->getAmount()->getAmount() / 100);
        $this->assertSame($this->currency, $report->getAmount()->getCurrency()->getCode());
        $this->assertSame($this->customerName, $report->getCustomerName());
        $this->assertSame($this->description, $report->getDescription());
        $this->assertSame($this->invoiceNumber, $report->getInvoiceNumber());
        $this->assertSame($this->payerHash, $report->getPayerHash());
        $this->assertSame($this->payment, $report->getPayment());
        $this->assertSame($this->paymentMethod, $report->getPaymentMethod());
        $this->assertSame($this->consumerBic, $report->getConsumerBic());
        $this->assertSame($this->consumerIban, $report->getConsumerIban());
        $this->assertSame($this->consumerIssuer, $report->getConsumerIssuer());
        $this->assertSame($this->consumerName, $report->getConsumerName());
        $this->assertSame($statuscode, $report->getStatusCode());
        $this->assertSame($statuscodeDetail, $report->getStatusCodeDetail());
        $this->assertSame($statusMessage, $report->getStatusMessage());
        $this->assertSame($this->timestamp, $report->getTimestamp()->format('Y-m-d H:i:s'));
        $this->assertSame($this->transactions, $report->getTransactions());
    }

    /**
     * @test
     */
    public function it_can_create_a_pending_report()
    {
        $statuscode = ResponseInterface::STATUS_PENDING_PROCESSING;
        $statusMessage = 'Pending processing';

        $data = [
            'BRQ_AMOUNT' => $this->amount,
            'BRQ_CURRENCY' => $this->currency,
            'BRQ_DESCRIPTION' => $this->description,
            'BRQ_INVOICENUMBER' => $this->invoiceNumber,
            'BRQ_PAYMENT' => $this->payment,
            'BRQ_PAYMENT_METHOD' => $this->paymentMethod,
            'BRQ_SERVICE_IDEAL_CONSUMERISSUER' => $this->consumerIssuer,
            'BRQ_STATUSCODE' => $statuscode,
            'BRQ_STATUSMESSAGE' => $statusMessage,
            'BRQ_TIMESTAMP' => $this->timestamp,
            'BRQ_TRANSACTIONS' => $this->transactions,
            'BRQ_SIGNATURE' => $this->signature
        ];

        $report = IdealTransactionReport::create($data);

        $this->assertInstanceOf(ReportInterface::class, $report);
        $this->assertSame($this->amount, $report->getAmount()->getAmount() / 100);
        $this->assertSame($this->currency, $report->getAmount()->getCurrency()->getCode());
        $this->assertSame($this->description, $report->getDescription());
        $this->assertSame($this->invoiceNumber, $report->getInvoiceNumber());
        $this->assertSame($this->payment, $report->getPayment());
        $this->assertSame($this->paymentMethod, $report->getPaymentMethod());
        $this->assertSame($this->consumerIssuer, $report->getConsumerIssuer());
        $this->assertSame($statuscode, $report->getStatusCode());
        $this->assertSame($statusMessage, $report->getStatusMessage());
        $this->assertSame($this->timestamp, $report->getTimestamp()->format('Y-m-d H:i:s'));
        $this->assertSame($this->transactions, $report->getTransactions());
    }

    /**
     * @test
     */
    public function it_can_create_a_failed_report()
    {
        $statuscode = ResponseInterface::STATUS_FAILURE;
        $statuscodeDetail = 'S994';
        $statusMessage = 'An error occurred while processing the transaction through DeutscheBankProcessor.';

        $data = [
            'BRQ_AMOUNT' => $this->amount,
            'BRQ_CURRENCY' => $this->currency,
            'BRQ_DESCRIPTION' => $this->description,
            'BRQ_INVOICENUMBER' => $this->invoiceNumber,
            'BRQ_PAYMENT' => $this->payment,
            'BRQ_PAYMENT_METHOD' => $this->paymentMethod,
            'BRQ_SERVICE_IDEAL_CONSUMERISSUER' => $this->consumerIssuer,
            'BRQ_STATUSCODE' => $statuscode,
            'BRQ_STATUSCODE_DETAIL' => $statuscodeDetail,
            'BRQ_STATUSMESSAGE' => $statusMessage,
            'BRQ_TIMESTAMP' => $this->timestamp,
            'BRQ_TRANSACTIONS' => $this->transactions,
            'BRQ_SIGNATURE' => $this->signature
        ];

        $report = IdealTransactionReport::create($data);

        $this->assertInstanceOf(ReportInterface::class, $report);
        $this->assertSame($this->amount, $report->getAmount()->getAmount() / 100);
        $this->assertSame($this->currency, $report->getAmount()->getCurrency()->getCode());
        $this->assertSame($this->description, $report->getDescription());
        $this->assertSame($this->invoiceNumber, $report->getInvoiceNumber());
        $this->assertSame($this->payment, $report->getPayment());
        $this->assertSame($this->paymentMethod, $report->getPaymentMethod());
        $this->assertSame($this->consumerIssuer, $report->getConsumerIssuer());
        $this->assertSame($statuscode, $report->getStatusCode());
        $this->assertSame($statuscodeDetail, $report->getStatusCodeDetail());
        $this->assertSame($statusMessage, $report->getStatusMessage());
        $this->assertSame($this->timestamp, $report->getTimestamp()->format('Y-m-d H:i:s'));
        $this->assertSame($this->transactions, $report->getTransactions());
    }

    /**
     * @test
     */
    public function it_can_create_a_rejected_report()
    {
        $statuscode = ResponseInterface::STATUS_REJECTED;
        $statuscodeDetail = 'S994';
        $statusMessage = 'An error occurred while processing the transaction through DeutscheBankProcessor.';

        $data = [
            'BRQ_AMOUNT' => $this->amount,
            'BRQ_CURRENCY' => $this->currency,
            'BRQ_DESCRIPTION' => $this->description,
            'BRQ_INVOICENUMBER' => $this->invoiceNumber,
            'BRQ_PAYMENT' => $this->payment,
            'BRQ_PAYMENT_METHOD' => $this->paymentMethod,
            'BRQ_SERVICE_IDEAL_CONSUMERISSUER' => $this->consumerIssuer,
            'BRQ_STATUSCODE' => $statuscode,
            'BRQ_STATUSCODE_DETAIL' => $statuscodeDetail,
            'BRQ_STATUSMESSAGE' => $statusMessage,
            'BRQ_TIMESTAMP' => $this->timestamp,
            'BRQ_TRANSACTIONS' => $this->transactions,
            'BRQ_SIGNATURE' => $this->signature
        ];

        $report = IdealTransactionReport::create($data);

        $this->assertInstanceOf(ReportInterface::class, $report);
        $this->assertSame($this->amount, $report->getAmount()->getAmount() / 100);
        $this->assertSame($this->currency, $report->getAmount()->getCurrency()->getCode());
        $this->assertSame($this->description, $report->getDescription());
        $this->assertSame($this->invoiceNumber, $report->getInvoiceNumber());
        $this->assertSame($this->payment, $report->getPayment());
        $this->assertSame($this->paymentMethod, $report->getPaymentMethod());
        $this->assertSame($this->consumerIssuer, $report->getConsumerIssuer());
        $this->assertSame($statuscode, $report->getStatusCode());
        $this->assertSame($statuscodeDetail, $report->getStatusCodeDetail());
        $this->assertSame($statusMessage, $report->getStatusMessage());
        $this->assertSame($this->timestamp, $report->getTimestamp()->format('Y-m-d H:i:s'));
        $this->assertSame($this->transactions, $report->getTransactions());
    }

    /**
     * @test
     */
    public function it_can_create_a_cancelled_report()
    {
        $statuscode = ResponseInterface::STATUS_CANCELLED_BY_USER;
        $statuscodeDetail = 'S994';
        $statusMessage = 'An error occurred while processing the transaction through DeutscheBankProcessor.';

        $data = [
            'BRQ_AMOUNT' => $this->amount,
            'BRQ_CURRENCY' => $this->currency,
            'BRQ_DESCRIPTION' => $this->description,
            'BRQ_INVOICENUMBER' => $this->invoiceNumber,
            'BRQ_PAYMENT' => $this->payment,
            'BRQ_PAYMENT_METHOD' => $this->paymentMethod,
            'BRQ_SERVICE_IDEAL_CONSUMERISSUER' => $this->consumerIssuer,
            'BRQ_STATUSCODE' => $statuscode,
            'BRQ_STATUSCODE_DETAIL' => $statuscodeDetail,
            'BRQ_STATUSMESSAGE' => $statusMessage,
            'BRQ_TIMESTAMP' => $this->timestamp,
            'BRQ_TRANSACTIONS' => $this->transactions,
            'BRQ_SIGNATURE' => $this->signature
        ];

        $report = IdealTransactionReport::create($data);

        $this->assertInstanceOf(ReportInterface::class, $report);
        $this->assertSame($this->amount, $report->getAmount()->getAmount() / 100);
        $this->assertSame($this->currency, $report->getAmount()->getCurrency()->getCode());
        $this->assertSame($this->description, $report->getDescription());
        $this->assertSame($this->invoiceNumber, $report->getInvoiceNumber());
        $this->assertSame($this->payment, $report->getPayment());
        $this->assertSame($this->paymentMethod, $report->getPaymentMethod());
        $this->assertSame($this->consumerIssuer, $report->getConsumerIssuer());
        $this->assertSame($statuscode, $report->getStatusCode());
        $this->assertSame($statuscodeDetail, $report->getStatusCodeDetail());
        $this->assertSame($statusMessage, $report->getStatusMessage());
        $this->assertSame($this->timestamp, $report->getTimestamp()->format('Y-m-d H:i:s'));
        $this->assertSame($this->transactions, $report->getTransactions());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_fails_when_data_is_missing()
    {
        $data = [
            'BRQ_AMOUNT' => $this->amount,
            'BRQ_CURRENCY' => $this->currency,
            'BRQ_DESCRIPTION' => $this->description,
            'BRQ_INVOICENUMBER' => $this->invoiceNumber,
            'BRQ_PAYMENT' => $this->payment,
            'BRQ_PAYMENT_METHOD' => $this->paymentMethod,
            'BRQ_SERVICE_IDEAL_CONSUMERISSUER' => $this->consumerIssuer,
        ];

        IdealTransactionReport::create($data);
    }
}
