<?php

namespace TreeHouse\BuckarooBundle\Response;

class IdealTransactionResponse extends AbstractTransactionResponse
{
    /**
     * The (ideal) URL to redirect the user to to complete the transaction.
     *
     * @var string
     */
    private $redirectUrl;

    /**
     * @inheritdoc
     *
     * @return $this
     */
    public static function create(array $data)
    {
        $response = parent::create($data);

        if (!isset($data['BRQ_REDIRECTURL'])) {
            throw new \InvalidArgumentException('Missing field: BRQ_REDIRECTURL');
        }

        $response->redirectUrl = $data['BRQ_REDIRECTURL'];

        return $response;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }
}
