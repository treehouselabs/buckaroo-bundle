<?php

namespace TreeHouse\BuckarooBundle\Report;

abstract class AbstractTransactionReport implements ReportInterface
{
    /**
     * @var string
     */
    private $invoiceNumber;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string
     */
    private $statusCodeDetail;

    /**
     * @var string
     */
    private $statusMessage;

    /**
     * @var \DateTime
     */
    private $timestamp;

    /**
     * @param string    $invoiceNumber
     * @param int       $statusCode
     * @param string    $statusMessage
     * @param \DateTime $timestamp
     * @param string    $statusCodeDetail
     */
    private function __construct($invoiceNumber, $statusCode, $statusMessage, \DateTime $timestamp, $statusCodeDetail = null)
    {
        $this->invoiceNumber = $invoiceNumber;
        $this->statusCode = $statusCode;
        $this->statusMessage = $statusMessage;
        $this->timestamp = $timestamp;
        $this->statusCodeDetail = $statusCodeDetail;
    }

    /**
     * @inheritdoc
     */
    public static function create(array $data)
    {
        static::ensureOptionalFields(['BRQ_STATUSCODE_DETAIL'], $data);
        static::checkRequiredFields(
            ['BRQ_INVOICENUMBER', 'BRQ_STATUSCODE', 'BRQ_STATUSMESSAGE', 'BRQ_TIMESTAMP'],
            $data
        );

        return new static(
            $data['BRQ_INVOICENUMBER'],
            $data['BRQ_STATUSCODE'],
            $data['BRQ_STATUSMESSAGE'],
            new \DateTime($data['BRQ_TIMESTAMP']),
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
