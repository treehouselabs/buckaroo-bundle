<?php

namespace TreeHouse\BuckarooBundle\Report;

use Money\Currency;
use Money\Money;

class TransferTransactionReport extends AbstractTransactionReport
{
    /**
     * @var Money
     */
    private $amount;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $payment;

    /**
     * @var string
     */
    private $transactionMethod;

    /**
     * @var string
     */
    private $transactionType;

    /**
     * @param array $data
     *
     * @return $this
     */
    public static function create(array $data)
    {
        $requiredFields = [
            'BRQ_AMOUNT',
            'BRQ_CURRENCY',
            'BRQ_DESCRIPTION',
            'BRQ_INVOICENUMBER',
            'BRQ_PAYMENT',
            'BRQ_STATUSCODE',
            'BRQ_STATUSMESSAGE',
            'BRQ_TIMESTAMP',
            'BRQ_TRANSACTIONS',
            'BRQ_TRANSACTION_METHOD',
            'BRQ_TRANSACTION_TYPE',
        ];

        static::checkRequiredFields($requiredFields, $data);

        if ('C001' !== $data['BRQ_TRANSACTION_TYPE']) {
            throw new \RuntimeException(
                sprintf('Expected to create a %s for a transfer transaction.', static::class)
            );
        }

        /** @var static $report */
        $report = parent::create($data);
        $report->amount = new Money(intval($data['BRQ_AMOUNT'] * 100), new Currency($data['BRQ_CURRENCY']));
        $report->description = $data['BRQ_DESCRIPTION'];
        $report->payment = $data['BRQ_PAYMENT'];
        $report->transactionMethod = $data['BRQ_TRANSACTION_METHOD'];
        $report->transactionType = $data['BRQ_TRANSACTION_TYPE'];

        return $report;
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
    public function getDescription()
    {
        return $this->description;
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
    public function getTransactionMethod()
    {
        return $this->transactionMethod;
    }

    /**
     * @return string
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }
}
