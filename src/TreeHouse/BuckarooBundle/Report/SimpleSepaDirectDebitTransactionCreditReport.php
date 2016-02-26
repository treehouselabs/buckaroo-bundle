<?php

namespace TreeHouse\BuckarooBundle\Report;

use Money\Currency;
use Money\Money;

/**
 * This report is meant to inform the system about SimpleSepaDirectDebitTransaction occurences
 * with a credited amount (the field BRQ_CREDIT_AMOUNT in the Bucakroo API).
 */
class SimpleSepaDirectDebitTransactionCreditReport extends AbstractSimpleSepaDirectDebitTransactionReport
{
    /**
     * Creates a credited transaction (reversed/rejected/bounced) report.
     *
     * @param array $data
     *
     * @return $this
     */
    public static function create(array $data)
    {
        $requiredFields = [
            'BRQ_AMOUNT_CREDIT',
            'BRQ_CURRENCY',
            'BRQ_CUSTOMER_NAME',
            'BRQ_INVOICENUMBER',
            'BRQ_PAYMENT',
            'BRQ_TRANSACTION_METHOD',
            'BRQ_TRANSACTION_TYPE',
        ];

        static::checkRequiredFields($requiredFields, $data);

        if ('C008' === $data['BRQ_TRANSACTION_TYPE']) {
            throw new \RuntimeException(
                sprintf(
                    'Expected to create a %s for a credit transaction. Got a regular transaction (type C008) instead. ' .
                    'Are you attempting to create a SimpleSepaDirectDebitTransactionDebitReport?',
                    static::class
                )
            );
        }

        $report = parent::create($data);
        $report->amount = new Money(intval($data['BRQ_AMOUNT_CREDIT'] * 100), new Currency($data['BRQ_CURRENCY']));
        $report->customerName = $data['BRQ_CUSTOMER_NAME'];
        $report->invoiceNumber = $data['BRQ_INVOICENUMBER'];
        $report->payment = $data['BRQ_PAYMENT'];
        $report->transactionMethod = $data['BRQ_TRANSACTION_METHOD'];
        $report->transactionType = $data['BRQ_TRANSACTION_TYPE'];
        $report->transactions = $data['BRQ_TRANSACTIONS'];

        return $report;
    }
}
