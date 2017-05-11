<?php

namespace TreeHouse\BuckarooBundle\Report;

use Money\Money;

abstract class AbstractSimpleSepaDirectDebitTransactionReport extends AbstractTransactionReport
{
    /**
     * The amount for the transaction.
     *
     * @var Money
     */
    protected $amount;

    /**
     * Name of the customer that initiated the transaction.
     *
     * @var string
     */
    protected $customerName;

    /**
     * The invoice number (internally stored as Transaction reference/compound).
     *
     * @var string
     */
    protected $invoiceNumber;

    /**
     * @var string
     */
    protected $mutationType;

    /**
     * Payment key, only filled in if the transaction lead to an actual payment.
     *
     * @var string
     */
    protected $payment;

    /**
     * The date on which the payment is collected.
     *
     * @var \DateTime
     */
    protected $collectDate;

    /**
     * The IBAN for the customer bank account on which the direct debit will be performed.
     *
     * @var string
     */
    protected $customerIban;

    /**
     * The type of direct debit that has been performed. Possible values:.
     *
     *  - OnOff: A single directdebit
     *  - First: The first of a recurrent sequence.
     *  - Recurring: The next direct debit in a recurring sequence
     *
     * @var string
     */
    protected $directDebitType;

    /**
     * The date on which the mandate was given by the customer.
     *
     * @var \DateTime
     */
    protected $mandateDate;

    /**
     * The reference of the given mandate.
     *
     * @var string
     */
    protected $mandateReference;

    /**
     * All Pay requests for this service require the basic gateway field StartRecurrent with its value set to true.
     *
     * @var bool
     */
    protected $startRecurrent;

    /**
     * The transaction method used. Should always return "SimpleSepaDirectDebit" in this case.
     *
     * @var string
     */
    protected $transactionMethod;

    /**
     * The transaction type. Common types are:.
     *
     *  - C008 for a payment
     *  - C500 for a refund by the merchant
     *  - C501 for a reversal by the customer
     *  - C502 for a rejected direct debit (due to wrong IBAN/account holder combination)
     *  - N522 for a bounced refund (due to wrong IBAN/account holder combination)
     *
     * @var string
     */
    protected $transactionType;

    /**
     * One or more transaction keys. One key if only a transaction was created or a payment with one underlying transaction.
     * Multiple keys if one payment has multiple underlying transactions. List of keys is comma separated.
     *
     * @var string
     */
    protected $transactions;

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
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * @return string
     */
    public function getMutationType()
    {
        return $this->mutationType;
    }

    /**
     * @return string
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @return \DateTime
     */
    public function getCollectDate()
    {
        return $this->collectDate;
    }

    /**
     * @return string
     */
    public function getCustomerIban()
    {
        return $this->customerIban;
    }

    /**
     * @return string
     */
    public function getDirectDebitType()
    {
        return $this->directDebitType;
    }

    /**
     * @return \DateTime
     */
    public function getMandateDate()
    {
        return $this->mandateDate;
    }

    /**
     * @return string
     */
    public function getMandateReference()
    {
        return $this->mandateReference;
    }

    /**
     * @return bool
     */
    public function isStartRecurrent()
    {
        return $this->startRecurrent;
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
