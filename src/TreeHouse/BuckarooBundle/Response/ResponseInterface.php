<?php

namespace TreeHouse\BuckarooBundle\Response;

interface ResponseInterface
{
    /**
     * List of TransactionResponse status codes.
     *
     * @see http://support.buckaroo.nl/index.php/Statuscodes
     */
    const STATUS_SUCCESS = 190;
    const STATUS_FAILURE = 490;
    const STATUS_VALIDATION_FAILURE = 491;
    const STATUS_TECHNICAL_ERROR = 492;
    const STATUS_REJECTED = 690;
    const STATUS_PENDING_INPUT = 790;
    const STATUS_PENDING_PROCESSING = 791;
    const STATUS_AWAITING_CONSUMER = 792;
    const STATUS_ON_HOLD = 793;
    const STATUS_CANCELLED_BY_USER = 890;
    const STATUS_CANCELLED_BY_MERCHANT = 891;

    /**
     * Possible values of the BRQ_APIRESULT response field.
     *
     * @see http://support.buckaroo.nl/index.php/NVP_Koppeling
     */
    const RESULT_SUCCESS = 'Success';         // action or transaction was successful
    const RESULT_REJECT = 'Reject';          // action or transaction was rejected by Buckaroo or the acquirer
    const RESULT_CANCEL = 'Cancel';          // action or transaction was cancelled
    const RESULT_FAIL = 'Fail';            // action or transaction has failed
    const RESULT_PENDING = 'Pending';         // action or transaction is pending (updates can be pushed later)
    const RESULT_ACTION_REQUIRED = 'ActionRequired';  // an action must be performed before the transaction can be completed (i.e. a redirect)
    const RESULT_WAITING = 'Waiting';         // the transaction is waiting for the customer (unsure if this is the last status)
    const RESULT_ON_HOLD = 'OnHold';          // the transaction has been put on hold for some reason (i.e. not enough credit), this is not a final status

    /**
     * @param array $data
     *
     * @return $this
     */
    public static function create(array $data);
}
