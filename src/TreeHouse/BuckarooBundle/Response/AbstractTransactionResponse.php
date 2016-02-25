<?php

namespace TreeHouse\BuckarooBundle\Response;

abstract class AbstractTransactionResponse implements ResponseInterface
{
    /**
     * The result of the call to the gateway (see ResponseInterface).
     *
     * @var string
     */
    private $apiResult;

    /**
     * The statuscode of the transaction (see ResponseInterface).
     *
     * @var int|null
     */
    private $statusCode;

    /**
     * A detail status code which provides an extra explanation for the current status of the transaction.
     *
     * @var string
     */
    private $statusCodeDetail;

    /**
     * A message explaining the current (detail)status.
     *
     * @var string|null
     */
    private $statusMessage;

    /**
     * The time at which the payment received it current status.
     *
     * @var \DateTime
     */
    private $timestamp;

    /**
     * The invoicenumber as provided in the request.
     *
     * @var string
     */
    private $invoiceNumber;

    /**
     * @param string    $apiResult
     * @param int       $statusCode
     * @param string    $statusCodeDetail
     * @param string    $statusMessage
     * @param \DateTime $timestamp
     * @param string    $invoiceNumber
     */
    private function __construct(
        $apiResult,
        $statusCode,
        $statusCodeDetail,
        $statusMessage,
        \DateTime $timestamp,
        $invoiceNumber
    ) {
        $this->apiResult = $apiResult;
        $this->statusCode = $statusCode;
        $this->statusCodeDetail = $statusCodeDetail;
        $this->statusMessage = $statusMessage;
        $this->timestamp = $timestamp;
        $this->invoiceNumber = $invoiceNumber;
    }

    /**
     * @inheritdoc
     */
    public static function create(array $data)
    {
        $requiredFields = [
            'BRQ_APIRESULT',
            'BRQ_STATUSCODE',
            'BRQ_STATUSMESSAGE',
            'BRQ_TIMESTAMP',
        ];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new \InvalidArgumentException(sprintf('Missing field: %s', $field));
            }
        }

        return new static(
            $data['BRQ_APIRESULT'],
            $data['BRQ_STATUSCODE'],
            isset($data['BRQ_STATUSCODE_DETAIL']) ? $data['BRQ_STATUSCODE_DETAIL'] : null,
            $data['BRQ_STATUSMESSAGE'],
            new \DateTime($data['BRQ_TIMESTAMP']),
            $data['BRQ_INVOICENUMBER']
        );
    }

    /**
     * @return string
     */
    public function getApiResult()
    {
        return $this->apiResult;
    }

    /**
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getStatusCodeDetail()
    {
        return $this->statusCodeDetail;
    }

    /**
     * @return string|null
     */
    public function getStatusMessage()
    {
        return $this->statusMessage;
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
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * @return bool
     */
    public function isInvalid()
    {
        return in_array($this->statusCode, [
            self::STATUS_FAILURE,
            self::STATUS_VALIDATION_FAILURE,
            self::STATUS_TECHNICAL_ERROR,
        ]);
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
     * @return string|null
     */
    public function getError()
    {
        if ($this->isSuccess()) {
            return null;
        }

        if (!$this->statusMessage) {
            return 'Unknown error';
        }

        return $this->statusMessage;
    }
}
