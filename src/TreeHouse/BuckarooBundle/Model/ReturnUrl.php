<?php

namespace TreeHouse\BuckarooBundle\Model;

class ReturnUrl
{
    /**
     * URL to return the user to when the payment was successful.
     *
     * @var string
     */
    private $success;

    /**
     * URL to return the user to when the transaction was cancelled (by the user).
     *
     * @var string
     */
    private $cancel;

    /**
     * URL to return the user to when an error occurred during the transaction.
     *
     * @var string
     */
    private $error;

    /**
     * URL to return the user to when the transaction was rejected by their bank.
     *
     * @var string
     */
    private $reject;

    /**
     * @param string $success
     */
    public function __construct($success)
    {
        $this->setSuccess($success);
    }

    /**
     * @param string $success
     *
     * @return $this
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * @return string
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @param string $cancel
     *
     * @return $this
     */
    public function setCancel($cancel)
    {
        $this->cancel = $cancel;

        return $this;
    }

    /**
     * @return string
     */
    public function getCancel()
    {
        return $this->cancel;
    }

    /**
     * @param string $error
     *
     * @return $this
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $reject
     *
     * @return $this
     */
    public function setReject($reject)
    {
        $this->reject = $reject;

        return $this;
    }

    /**
     * @return string
     */
    public function getReject()
    {
        return $this->reject;
    }
}
