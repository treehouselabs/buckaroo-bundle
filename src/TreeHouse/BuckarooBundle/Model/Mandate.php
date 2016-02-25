<?php

namespace TreeHouse\BuckarooBundle\Model;

/**
 * Value object representing the proof that a payment was approved by the consumer.
 */
class Mandate
{
    /**
     * The mandate reference used for payment.
     *
     * @var string
     */
    private $reference;

    /**
     * The signing date of the mandate that was used.
     *
     * @var \DateTime
     */
    private $date;

    /**
     * @param string    $reference
     * @param \DateTime $date
     */
    public function __construct($reference, \DateTime $date)
    {
        $this->reference = $reference;
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}
