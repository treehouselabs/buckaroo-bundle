<?php

namespace TreeHouse\BuckarooBundle\Client;

use TreeHouse\BuckarooBundle\Request\RequestInterface;
use TreeHouse\BuckarooBundle\Response\ResponseInterface;

interface ClientInterface
{
    /**
     * Sends a request to a Buckaroo gateway. The response returned is determined by the createResponse method of said request.
     *
     * @param RequestInterface $request
     *
     * @throws \RuntimeException If the response content can not be parsed or it has an unexpected status code (not 200)
     *
     * @return ResponseInterface
     */
    public function send(RequestInterface $request);
}
