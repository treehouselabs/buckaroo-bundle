<?php

namespace TreeHouse\BuckarooBundle\Request;

use TreeHouse\BuckarooBundle\Model\Mandate;
use TreeHouse\BuckarooBundle\Response\SimpleSepaDirectDebitTransactionResponse;

class SimpleSepaDirectDebitTransactionRequest extends AbstractTransactionRequest
{
    /**
     * The IBAN for the customer bank account on which the direct debit should be performed.
     *
     * @var string
     */
    private $customerIban;

    /**
     * The name of the accountholder for the account on which the direct debit should be performed.
     *
     * @var string
     */
    private $customerAccountName;

    /**
     * The BIC code for the customer bank account on which the direct debit should be performed.
     * This is only required when the IBAN is not Dutch.
     *
     * @var string|null
     */
    private $customerBic;

    /**
     * The mandate under which the direct debit falls.
     *
     * @var Mandate
     */
    private $mandate;

    /**
     * The date on which the direct debit should collected from the consumer account.
     * The actual direct debit will be sent to the bank 5 working days earlier.
     *
     * @var \DateTime|null
     */
    private $datetimeCollect;

    /**
     * @param string $iban
     */
    public function setCustomerIban($iban)
    {
        $this->customerIban = $iban;
    }

    /**
     * @param string|null $bic
     */
    public function setCustomerBic($bic)
    {
        $this->customerBic = $bic;
    }

    /**
     * @param string $accountName
     */
    public function setCustomerAccountName($accountName)
    {
        $this->customerAccountName = $accountName;
    }

    /**
     * @param \DateTime $datetimeCollect
     */
    public function setDatetimeCollect(\DateTime $datetimeCollect)
    {
        $this->datetimeCollect = $datetimeCollect;
    }

    /**
     * @param Mandate $mandate
     */
    public function setMandate(Mandate $mandate)
    {
        $this->mandate = $mandate;
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'payment_method' => 'simplesepadirectdebit',
            'CollectDate' => $this->datetimeCollect ? $this->datetimeCollect->format('Y-m-d H:i:s') : '',
            'customeraccountname' => $this->customerAccountName,
            'service_simplesepadirectdebit_CustomerBIC' => (string) $this->customerBic,
            'service_simplesepadirectdebit_CustomerIBAN' => $this->customerIban,
            'service_simplesepadirectdebit_MandateDate' => $this->mandate->getDate()->format('Y-m-d'),
            'service_simplesepadirectdebit_MandateReference' => $this->mandate->getReference(),
            'service_simplesepadirectdebit_action' => 'Pay',
            'StartRecurrent' => true,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getResponseClass()
    {
        return SimpleSepaDirectDebitTransactionResponse::class;
    }
}
