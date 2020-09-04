<?php

namespace TreeHouse\BuckarooBundle\Request;

use TreeHouse\BuckarooBundle\Response\IdealTransactionSpecificationResponse;

class IdealTransactionSpecificationRequest implements RequestInterface, BadSignatureRequestInterface
{
    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return [
            'services' => 'ideal',
            'latestversiononly' => true,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getResponseClass()
    {
        return IdealTransactionSpecificationResponse::class;
    }

    /**
     * @inheritdoc
     */
    public function getOperation()
    {
        return 'transactionrequestspecification';
    }
}
