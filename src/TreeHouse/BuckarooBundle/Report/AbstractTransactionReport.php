<?php

namespace TreeHouse\BuckarooBundle\Report;

abstract class AbstractTransactionReport implements ReportInterface
{
    /**
     * The invoicenumber as provided in the request.
     *
     * @var string
     */
    private $invoiceNumber;

    /**
     * The status code of the transaction.
     *
     * @var int
     */
    private $statusCode;

    /**
     * A detail status code which provides an yes extra explanation for the
     * current status of the transaction.
     *
     * @var string
     */
    private $statusCodeDetail;

    /**
     * A message explaining the current (detail)status.
     *
     * @var string
     */
    private $statusMessage;

    /**
     * The time at which the payment received it current status.
     *
     * @var \DateTime
     */
    private $timestamp;

    /**
     * One or more transaction keys. One key if only a transaction was created
     * or a payment with one underlying transaction. Multiple keys if one
     * payment has multiple underlying transactions. List of keys is comma
     * separated.
     *
     * @var string
     */
    private $transactions;

    /**
     * @param string    $invoiceNumber
     * @param int       $statusCode
     * @param string    $statusMessage
     * @param \DateTime $timestamp
     * @param string    $transactions
     * @param string    $statusCodeDetail
     */
    private function __construct($invoiceNumber, $statusCode, $statusMessage, \DateTime $timestamp, $transactions, $statusCodeDetail = null)
    {
        $this->invoiceNumber = $invoiceNumber;
        $this->statusCode = $statusCode;
        $this->statusMessage = $statusMessage;
        $this->timestamp = $timestamp;
        $this->transactions = $transactions;
        $this->statusCodeDetail = $statusCodeDetail;
    }

    /**
     * @inheritdoc
     */
    public static function create(array $data)
    {
        $requiredFields = [
            'BRQ_INVOICENUMBER',
            'BRQ_STATUSCODE',
            'BRQ_STATUSMESSAGE',
            'BRQ_TIMESTAMP',
            'BRQ_TRANSACTIONS',
        ];
        $optionalFields = ['BRQ_STATUSCODE_DETAIL'];

        static::checkRequiredFields($requiredFields, $data);
        static::ensureOptionalFields($optionalFields, $data);

        return new static(
            $data['BRQ_INVOICENUMBER'],
            $data['BRQ_STATUSCODE'],
            $data['BRQ_STATUSMESSAGE'],
            new \DateTime($data['BRQ_TIMESTAMP']),
            $data['BRQ_TRANSACTIONS'],
            $data['BRQ_STATUSCODE_DETAIL']
        );
    }

    /**
     * @inheritdoc
     */
    public function getError()
    {
        if ($this->isSuccess()) {
            return null;
        }

        return $this->statusMessage;
    }

    /**
     * @return bool
     */
    public function isPending()
    {
        return in_array($this->statusCode, [
            self::STATUS_PENDING_INPUT,
            self::STATUS_PENDING_PROCESSING,
            self::STATUS_AWAITING_CONSUMER,
            self::STATUS_ON_HOLD,
        ]);
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return self::STATUS_SUCCESS === (int) $this->statusCode;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getStatusMessage()
    {
        return $this->statusMessage;
    }

    /**
     * @return string
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * @return string|null
     */
    public function getStatusCodeDetail()
    {
        return $this->statusCodeDetail;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * Checks that the given fields are in the array and are not `null`.
     *
     * @param array $fields
     * @param array $data
     *
     * @throws \InvalidArgumentException
     */
    protected static function checkRequiredFields(array $fields, array $data)
    {
        foreach ($fields as $field) {
            if (!isset($data[$field])) {
                throw new \InvalidArgumentException(sprintf('Missing field: %s', $field));
            }
        }
    }

    /**
     * Ensures that the given fields exist in the $data array.
     * If a field does not exist, it will be added with a `null` value.
     *
     * @param array $fields
     * @param array $data
     */
    protected static function ensureOptionalFields(array $fields, array &$data)
    {
        foreach ($fields as $field) {
            if (!isset($data[$field])) {
                $data[$field] = null;
            }
        }
    }
}
