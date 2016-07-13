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
     * Description displayed with the transaction (visible to customer)
     *
     * @var string|null
     */
    private $description;

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
     * @param string|null $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
            'description' => (string) $this->description,
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
