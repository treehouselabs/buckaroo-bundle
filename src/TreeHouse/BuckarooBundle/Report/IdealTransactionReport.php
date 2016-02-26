<?php

namespace TreeHouse\BuckarooBundle\Report;

use Money\Currency;
use Money\Money;

class IdealTransactionReport extends AbstractTransactionReport
{
    /**
     * The debit amount for the transaction.
     *
     * @var Money
     */
    private $amount;

    /**
     * Name of the customer that initiated the transaction.
     *
     * @var string
     */
    private $customerName;

    /**
     * Description of the transaction, also displayed in bank statements.
     *
     * @var string
     */
    private $description;

    /**
     * E.g. 9ed930548902988eb872ce58903105cc9bd7040fa6a08dfa53bcd086cd6973398af78b969857ca0b1d2fef1132981d633135437860a0fda36305cdd14b713f92.
     *
     * @var string
     */
    private $payerHash;

    /**
     * Payment key, only filled in if the transaction lead to an actual payment.
     *
     * @var string
     */
    private $payment;

    /**
     * The service code for the payment method used, in this case 'ideal'.
     *
     * @var string
     */
    private $paymentMethod;

    /**
     * Name of the consumer who will pay for this transaction.
     *
     * @var string
     */
    private $consumerName;

    /**
     * The BIC code for the customer bank account on which the direct debit will be performed.
     *
     * @var string
     */
    private $consumerBic;

    /**
     * The IBAN for the customer bank account on which the direct debit will be performed.
     *
     * @var string
     */
    private $consumerIban;

    /**
     * The issuing bank that handles this transaction.
     *
     * @var string
     */
    private $consumerIssuer;

    /**
     * One or more transaction keys. One key if only a transaction was created or a payment with one underlying transaction.
     * Multiple keys if one payment has multiple underlying transactions. List of keys is comma separated.
     *
     * @var string
     */
    private $transactions;

    /**
     * @inheritdoc
     *
     * @return $this
     */
    public static function create(array $data)
    {
        $requiredFields = [
            'BRQ_AMOUNT',
            'BRQ_CURRENCY',
            'BRQ_DESCRIPTION',
            'BRQ_PAYMENT',
            'BRQ_PAYMENT_METHOD',
            'BRQ_SERVICE_IDEAL_CONSUMERISSUER',
            'BRQ_TRANSACTIONS',
        ];

        $optionalFields = [
            'BRQ_CUSTOMER_NAME',
            'BRQ_PAYER_HASH',
            'BRQ_SERVICE_IDEAL_CONSUMERBIC',
            'BRQ_SERVICE_IDEAL_CONSUMERIBAN',
            'BRQ_SERVICE_IDEAL_CONSUMERNAME',
        ];

        static::checkRequiredFields($requiredFields, $data);
        static::ensureOptionalFields($optionalFields, $data);

        $report = parent::create($data);
        $report->amount = new Money(intval($data['BRQ_AMOUNT'] * 100), new Currency($data['BRQ_CURRENCY']));
        $report->description = $data['BRQ_DESCRIPTION'];
        $report->customerName = $data['BRQ_CUSTOMER_NAME'];
        $report->payerHash = $data['BRQ_PAYER_HASH'];
        $report->payment = $data['BRQ_PAYMENT'];
        $report->paymentMethod = $data['BRQ_PAYMENT_METHOD'];
        $report->consumerBic = $data['BRQ_SERVICE_IDEAL_CONSUMERBIC'];
        $report->consumerIban = $data['BRQ_SERVICE_IDEAL_CONSUMERIBAN'];
        $report->consumerIssuer = $data['BRQ_SERVICE_IDEAL_CONSUMERISSUER'];
        $report->consumerName = $data['BRQ_SERVICE_IDEAL_CONSUMERNAME'];
        $report->transactions = $data['BRQ_TRANSACTIONS'];

        return $report;
    }

    /**
     * @return string
     */
    public function getConsumerIban()
    {
        return $this->consumerIban;
    }

    /**
     * @return string
     */
    public function getConsumerName()
    {
        return $this->consumerName;
    }

    /**
     * @return Money
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getPayerHash()
    {
        return $this->payerHash;
    }

    /**
     * @return string
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @return string
     */
    public function getConsumerBic()
    {
        return $this->consumerBic;
    }

    /**
     * @return string
     */
    public function getConsumerIssuer()
    {
        return $this->consumerIssuer;
    }

    /**
     * @return string
     */
    public function getTransactions()
    {
        return $this->transactions;
    }
}
