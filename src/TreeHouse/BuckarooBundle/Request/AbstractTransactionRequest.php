<?php

namespace TreeHouse\BuckarooBundle\Request;

use Money\Money;

abstract class AbstractTransactionRequest implements RequestInterface
{
    /**
     * The debit amount for the transaction.
     *
     * @var Money
     */
    private $amount;

    /**
     * A reference used to identify this transaction between different systems.
     *
     * @var string
     */
    private $invoiceNumber;

    /**
     * @param Money $amount
     */
    public function setAmount(Money $amount)
    {
        $this->amount = $amount;
    }

    /**
     * @param string $invoiceNumber
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return [
            'amount' => number_format($this->amount->getAmount() / 100, 2, '.', ''),
            'currency' => $this->amount->getCurrency()->getCode(),
            'invoicenumber' => $this->invoiceNumber,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getOperation()
    {
        return null;
    }
}
