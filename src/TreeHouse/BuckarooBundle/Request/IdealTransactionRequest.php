<?php

namespace TreeHouse\BuckarooBundle\Request;

use TreeHouse\BuckarooBundle\Model\ReturnUrl;
use TreeHouse\BuckarooBundle\Response\IdealTransactionResponse;

class IdealTransactionRequest extends AbstractTransactionRequest
{
    /**
     * BIC code for the issuing bank of the consumer (e.g. INGBNL2A). Determines the redirect URL the user gets sent to.
     *
     * @var string
     */
    private $issuer;

    /**
     * The URL(s) to return the user to after he/she completes the transfer with iDeal.
     *
     * @var ReturnUrl
     */
    private $returnUrl;

    /**
     * Description of this transaction, this is also displayed in bank statements.
     *
     * @var string|null
     */
    private $description;

    /**
     * @param string $issuer
     */
    public function setIssuer($issuer)
    {
        $this->issuer = $issuer;
    }

    /**
     * @param ReturnUrl $returnUrl
     */
    public function setReturnUrl(ReturnUrl $returnUrl)
    {
        $this->returnUrl = $returnUrl;
    }

    /**
     * @param string $description
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
        return array_merge(parent::toArray(), [
            'description' => $this->description,
            'payment_method' => 'ideal',
            'return' => $this->returnUrl->getSuccess(),
            'returncancel' => $this->returnUrl->getCancel(),
            'returnerror' => $this->returnUrl->getError(),
            'returnreject' => $this->returnUrl->getReject(),
            'service_ideal_action' => 'Pay',
            'service_ideal_issuer' => $this->issuer,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getResponseClass()
    {
        return IdealTransactionResponse::class;
    }
}
