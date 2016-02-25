<?php

namespace TreeHouse\BuckarooBundle\Response;

class IdealTransactionSpecificationResponse implements ResponseInterface
{
    /**
     * A list of possible issuers to choose from, indexed by their BIC code
     * This can be used in an ideal payment form to fill an issuer selection menu with.
     *
     * @var array
     */
    private $issuers = [];

    /**
     * @param array $issuers
     */
    private function __construct(array $issuers)
    {
        $this->issuers = $issuers;
    }

    /**
     * @inheritdoc
     *
     * @return $this
     */
    public static function create(array $data)
    {
        $keyPrefix = 'BRQ_SERVICES_2_ACTIONDESCRIPTION_1_REQUESTPARAMETERS_1_LISTITEMDESCRIPTION_';
        $firstKey = sprintf('%s%d_VALUE', $keyPrefix, 1);

        if (!array_key_exists($firstKey, $data)) {
            throw new \RuntimeException(sprintf(
                'Could not find key with issuers in the specification response: %s',
                $firstKey
            ));
        }

        $valueKey = $firstKey;
        $labelKey = $keyPrefix . '1_DESCRIPTION';
        $i = 1;

        $issuers = [];

        while (array_key_exists($valueKey, $data) && array_key_exists($labelKey, $data)) {
            $value = $data[$valueKey];
            $label = $data[$labelKey];
            $issuers[$value] = trim($label);
            ++$i;
            $valueKey = sprintf('%s%d_VALUE', $keyPrefix, $i);
            $labelKey = sprintf('%s%d_DESCRIPTION', $keyPrefix, $i);
        }

        return new self($issuers);
    }

    /**
     * @return array
     */
    public function getIssuers()
    {
        return $this->issuers;
    }
}
