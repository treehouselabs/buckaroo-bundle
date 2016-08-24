<?php

namespace TreeHouse\BuckarooBundle\Client;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use TreeHouse\BuckarooBundle\Request\BadSignatureRequestInterface;
use TreeHouse\BuckarooBundle\Request\RequestInterface;
use TreeHouse\BuckarooBundle\Response\ResponseInterface;
use TreeHouse\BuckarooBundle\SignatureGenerator;

abstract class AbstractClient implements ClientInterface
{
    /**
     * @var GuzzleClient
     */
    protected $client;

    /**
     * @var SignatureGenerator
     */
    protected $signatureGenerator;

    /**
     * @var string
     */
    protected $websiteKey;

    /**
     * @var bool
     */
    protected $test;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param GuzzleClient       $client
     * @param SignatureGenerator $signatureGenerator
     * @param string             $websiteKey
     * @param bool               $test
     * @param LoggerInterface    $logger
     */
    public function __construct(
        GuzzleClient $client,
        SignatureGenerator $signatureGenerator,
        $websiteKey,
        $test = false,
        LoggerInterface $logger = null
    ) {
        $this->client = $client;
        $this->signatureGenerator = $signatureGenerator;
        $this->websiteKey = $websiteKey;
        $this->test = $test;
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * @inheritdoc
     *
     * Base method for sending requests to the Buckaroo NVP gateway.
     */
    public function send(RequestInterface $request)
    {
        $data = $this->prefixFields($request->toArray());
        $url = $this->getGatewayUrl($request, $this->test);

        $data['brq_websitekey'] = $this->websiteKey;
        $data['brq_signature'] = $this->signatureGenerator->generate($data);

        $responseData = $this->sendData($data, $url);

        return $this->createResponse($request, $responseData);
    }

    /**
     * @param array       $data
     * @param string|null $signature
     */
    public function validate(array $data, $signature = null)
    {
        if ($signature === null) {
            $signature = $data['BRQ_SIGNATURE'];
            unset($data['BRQ_SIGNATURE']);
        }

        $expectedSignature = $this->signatureGenerator->generate($data);

        if ($signature !== $expectedSignature) {
            $this->logger->debug(sprintf(
                'Signature mismatch! Got "%s" but expected "%s"',
                $signature,
                $expectedSignature
            ), $data);

            throw new \InvalidArgumentException(sprintf(
                'Invalid signature for the given action\'s response data: %s',
                $signature
            ));
        }
    }

    /**
     * @param RequestInterface $request
     * @param array            $data
     *
     * @return ResponseInterface
     */
    private function createResponse(RequestInterface $request, array $data)
    {
        // some requests have responses (like the iDeal specification response) that
        // do not return a signature that we can reproduce
        // TODO find out why this happens
        $checkSignature = !$request instanceof BadSignatureRequestInterface;

        /** @var ResponseInterface $responseClass */
        $responseClass = $request->getResponseClass();
        $data = $this->normalizeIncomingData($data, $checkSignature);
        $response = $responseClass::create($data);

        if (!$response instanceof ResponseInterface) {
            throw new \RuntimeException(sprintf(
                'Expected response to be an instance of %s, got: %s',
                ResponseInterface::class,
                get_class($response)
            ));
        }

        return $response;
    }

    /**
     * @param array $data
     * @param bool  $checkSignature
     *
     * @return array
     */
    private function normalizeIncomingData(array $data, $checkSignature = true)
    {
        if (!isset($data['BRQ_SIGNATURE'])) {
            throw new \InvalidArgumentException('Result data does not contain a signature');
        }

        $signature = $data['BRQ_SIGNATURE'];
        unset($data['BRQ_SIGNATURE']);

        if ($checkSignature) {
            $this->validate($data, $signature);
        }

        unset($data['BRQ_TEST']);
        unset($data['BRQ_WEBSITEKEY']);

        return $data;
    }

    /**
     * @param array $fields
     *
     * @return array
     */
    private function prefixFields(array $fields)
    {
        $prefixed = [];
        foreach ($fields as $field => $value) {
            list($prefix) = explode('_', $field);
            if (!in_array(mb_strtolower($prefix), ['add', 'brq', 'cust'])) {
                $prefixed['brq_' . $field] = $value;
            } else {
                $prefixed[$field] = $value;
            }
        }

        return $prefixed;
    }

    /**
     * This method is responsible for sending a (normalized) set of data
     * and returning the (normalized) responding data.
     *
     * NOTE: The only responsibility extending clients should have is how the normalized data is sent and received
     *
     * @param array  $data
     * @param string $url
     *
     * @return array
     */
    abstract protected function sendData(array $data, $url);

    /**
     * @param RequestInterface $request
     * @param bool             $test
     *
     * @return string
     */
    abstract protected function getGatewayUrl(RequestInterface $request, $test = false);
}
