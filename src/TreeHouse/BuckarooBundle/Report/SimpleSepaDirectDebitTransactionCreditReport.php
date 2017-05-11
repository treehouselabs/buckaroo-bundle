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
     * @var string|null
     */
    private $reasonCode;

    /**
     * @var string|null
     */
    private $reasonExplanation;

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
            'BRQ_TRANSACTION_TYPE',
        ];

        $optionalFields = [
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_REASONCODE',
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_REASONEXPLANATION',
        ];

        static::checkRequiredFields($requiredFields, $data);
        static::ensureOptionalFields($optionalFields, $data);

        if ('C008' === $data['BRQ_TRANSACTION_TYPE']) {
            throw new \RuntimeException(
                sprintf(
                    'Expected to create a %s for a credit transaction. Got a regular transaction (type C008) instead. ' .
                    'Are you attempting to create a SimpleSepaDirectDebitTransactionDebitReport?',
                    static::class
                )
            );
        }

        /** @var static $report */
        $report = parent::create($data);
        $report->amount = new Money(intval($data['BRQ_AMOUNT_CREDIT'] * 100), new Currency($data['BRQ_CURRENCY']));
        $report->customerName = $data['BRQ_CUSTOMER_NAME'];
        $report->invoiceNumber = $data['BRQ_INVOICENUMBER'];
        $report->payment = $data['BRQ_PAYMENT'];
        $report->transactionMethod = isset($data['BRQ_TRANSACTION_METHOD']) ? $data['BRQ_TRANSACTION_METHOD'] : AbstractSimpleSepaDirectDebitTransactionReport::DEFAULT_TRANSACTION_METHOD;
        $report->transactionType = $data['BRQ_TRANSACTION_TYPE'];
        $report->transactions = $data['BRQ_TRANSACTIONS'];
        $report->reasonCode = $data['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_REASONCODE'];
        $report->reasonExplanation = $data['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_REASONEXPLANATION'];

        return $report;
    }

    /**
     * @return string|null
     */
    public function getReasonCode()
    {
        return $this->reasonCode;
    }

    /**
     * @return string|null
     */
    public function getReasonExplanation()
    {
        return $this->reasonExplanation;
    }
}
