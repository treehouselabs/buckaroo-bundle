<?php

namespace TreeHouse\BuckarooBundle\Report;

use Money\Currency;
use Money\Money;

class SimpleSepaDirectDebitTransactionDebitReport extends AbstractSimpleSepaDirectDebitTransactionReport
{
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
            'BRQ_CUSTOMER_NAME',
            'BRQ_INVOICENUMBER',
            'BRQ_PAYMENT',
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_COLLECTDATE',
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_CUSTOMERIBAN',
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_DIRECTDEBITTYPE',
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_MANDATEDATE',
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_MANDATEREFERENCE',
            'BRQ_STARTRECURRENT',
            'BRQ_TRANSACTION_TYPE',
        ];

        static::checkRequiredFields($requiredFields, $data);

        if ('C008' !== $data['BRQ_TRANSACTION_TYPE']) {
            throw new \RuntimeException(
                sprintf(
                    'Expected to create a %s for a debit transaction. Got a credit transaction (type %s) instead. ' .
                    'Are you attempting to create a SimpleSepaDirectDebitTransactionDebitReport?',
                    static::class,
                    $data['BRQ_TRANSACTION_TYPE']
                )
            );
        }

        /** @var static $report */
        $report = parent::create($data);
        $report->amount = new Money(intval($data['BRQ_AMOUNT'] * 100), new Currency($data['BRQ_CURRENCY']));
        $report->customerName = $data['BRQ_CUSTOMER_NAME'];
        $report->invoiceNumber = $data['BRQ_INVOICENUMBER'];
        $report->payment = $data['BRQ_PAYMENT'];
        $report->collectDate = new \DateTime($data['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_COLLECTDATE']);
        $report->customerIban = $data['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_CUSTOMERIBAN'];
        $report->directDebitType = $data['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_DIRECTDEBITTYPE'];
        $report->mandateDate = new \DateTime($data['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_MANDATEDATE']);
        $report->mandateReference = $data['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_MANDATEREFERENCE'];
        $report->startRecurrent = (bool) $data['BRQ_STARTRECURRENT'];
        $report->transactionMethod = isset($data['BRQ_TRANSACTION_METHOD']) ? $data['BRQ_TRANSACTION_METHOD'] : AbstractSimpleSepaDirectDebitTransactionReport::DEFAULT_TRANSACTION_METHOD;
        $report->transactionType = $data['BRQ_TRANSACTION_TYPE'];

        return $report;
    }
}
