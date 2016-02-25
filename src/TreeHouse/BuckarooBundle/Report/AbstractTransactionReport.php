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
        return new static(
            $data['BRQ_INVOICENUMBER'],
            $data['BRQ_STATUSCODE'],
            $data['BRQ_STATUSMESSAGE'],
            new \DateTime($data['BRQ_TIMESTAMP']),
            isset($data['BRQ_STATUSCODE_DETAIL']) ? $data['BRQ_STATUSCODE_DETAIL'] : null
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
}
