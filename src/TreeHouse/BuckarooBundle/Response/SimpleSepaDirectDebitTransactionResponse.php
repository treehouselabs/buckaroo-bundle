<?php

namespace TreeHouse\BuckarooBundle\Response;

use Money\Currency;
use Money\Money;
use TreeHouse\BuckarooBundle\Model\ConsumerMessage;
use TreeHouse\BuckarooBundle\Model\Mandate;

class SimpleSepaDirectDebitTransactionResponse extends AbstractTransactionResponse
{
    /**
     * The debit amount for the transaction.
     *
     * @var Money
     */
    private $amount;

    /**
     * @var ConsumerMessage
     */
    private $consumerMessage;

    /**
     * The name of the customer who will perform the direct debit.
     *
     * @var string
     */
    private $customerName;

    /**
     * The IBAN for the customer bank account on which the direct debit will be performed.
     *
     * @var string
     */
    private $customerIban;

    /**
     * The BIC code for the customer bank account on which the direct debit will be performed.
     *
     * @var string
     */
    private $customerBic;

    /**
     * Payment key, only filled in if the transaction lead to an actual payment.
     *
     * @var string
     */
    private $payment;

    /**
     * The service code of the method that was used to pay.
     *
     * @var string
     */
    private $paymentMethod;

    /**
     * True if this is the first direct debit of a series of recurrent direct debits, false otherwise.
     *
     * @var bool
     */
    private $startRecurrent;

    /**
     * One or more transaction keys. One key if only a transaction was created or a payment with one underlying transaction.
     * Multiple keys if one payment has multiple underlying transactions. List of keys is comma separated.
     *
     * @var string
     */
    private $transactions;

    /**
     * The expected date on which the direct debit will be collected from the consumer account. This can differ from
     * the requested collect date, due to a correction for the needed work days to process a direct debit.
     *
     * @var \DateTime
     */
    private $datetimeCollect;

    /**
     * The mandate used for the direct debit.
     *
     * @var Mandate
     */
    private $mandate;

    /**
     * Returns the type of direct debit that is going to be performed. Possible values:
     *  - OnOff: A single directdebit
     *  - First: The first of a recurrent sequence.
     *  - Recurring: The next direct debit in a recurring sequence.
     *
     * @var string
     */
    private $directDebitType;

    /**
     * @inheritdoc
     *
     * @return $this
     */
    public static function create(array $data)
    {
        $response = parent::create($data);

        if ($response->isInvalid()) {
            throw new \RuntimeException(sprintf(
                'The transaction with invoice number %s resulted in a %d response. The transaction cannot be completed: %s',
                $response->getInvoiceNumber(),
                $response->getStatusCode(),
                $response->getError()
            ));
        }

        $requiredFields = [
            'BRQ_AMOUNT',
            'BRQ_CURRENCY',
            'BRQ_CONSUMERMESSAGE_TITLE',
            'BRQ_CONSUMERMESSAGE_HTMLTEXT',
            'BRQ_CONSUMERMESSAGE_PLAINTEXT',
            'BRQ_CONSUMERMESSAGE_MUSTREAD',
            'BRQ_CUSTOMER_NAME',
            'BRQ_PAYMENT',
            'BRQ_PAYMENT_METHOD',
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_COLLECTDATE',
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_CUSTOMERIBAN',
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_DIRECTDEBITTYPE',
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_MANDATEDATE',
            'BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_MANDATEREFERENCE',
            'BRQ_STARTRECURRENT',
            'BRQ_TRANSACTIONS',
        ];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new \InvalidArgumentException(sprintf('Missing field: %s', $field));
            }
        }

        $response->amount = new Money(intval($data['BRQ_AMOUNT'] * 100), new Currency($data['BRQ_CURRENCY']));
        $response->consumerMessage = new ConsumerMessage(
            isset($data['BRQ_CONSUMERMESSAGE_CULTURE']) ? $data['BRQ_CONSUMERMESSAGE_CULTURE'] : null,
            $data['BRQ_CONSUMERMESSAGE_TITLE'],
            $data['BRQ_CONSUMERMESSAGE_HTMLTEXT'],
            $data['BRQ_CONSUMERMESSAGE_PLAINTEXT'],
            $data['BRQ_CONSUMERMESSAGE_MUSTREAD']
        );

        $response->payment = $data['BRQ_PAYMENT'];
        $response->paymentMethod = $data['BRQ_PAYMENT_METHOD'];
        $response->startRecurrent = $data['BRQ_STARTRECURRENT'];
        $response->transactions = $data['BRQ_TRANSACTIONS'];
        $response->datetimeCollect = new \DateTime($data['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_COLLECTDATE']);
        $response->customerName = $data['BRQ_CUSTOMER_NAME'];
        $response->customerBic = isset($data['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_CUSTOMERBIC']) ? $data['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_CUSTOMERBIC'] : null;
        $response->customerIban = $data['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_CUSTOMERIBAN'];
        $response->directDebitType = $data['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_DIRECTDEBITTYPE'];

        $response->mandate = new Mandate(
            $data['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_MANDATEREFERENCE'],
            new \DateTime($data['BRQ_SERVICE_SIMPLESEPADIRECTDEBIT_MANDATEDATE'])
        );

        return $response;
    }

    /**
     * @return Money
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return ConsumerMessage
     */
    public function getConsumerMessage()
    {
        return $this->consumerMessage;
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
    public function getCustomerBic()
    {
        return $this->customerBic;
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
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
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
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * @return \DateTime
     */
    public function getCollectDate()
    {
        return $this->datetimeCollect;
    }

    /**
     * @return Mandate
     */
    public function getMandate()
    {
        return $this->mandate;
    }

    /**
     * @return string
     */
    public function getDirectDebitType()
    {
        return $this->directDebitType;
    }
}
