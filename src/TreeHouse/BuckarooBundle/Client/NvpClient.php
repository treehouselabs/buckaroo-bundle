<?php

namespace TreeHouse\BuckarooBundle\Client;

use GuzzleHttp\Exception\TransferException;
use TreeHouse\BuckarooBundle\Request\RequestInterface;

class NvpClient extends AbstractClient
{
    /**
     * @inheritdoc
     */
    protected function sendData(array $data, $url)
    {
        $requestOptions = ['form_params' => $data, 'connect_timeout' => 5];

        try {
            // Log the POST request to the Buckaroo log
            $this->logger->debug($url, $requestOptions);
            $response = $this->client->post($url, $requestOptions);
        } catch (TransferException $e) {
            throw new \RuntimeException(sprintf('Failed to send request to Buckaroo: %s', $e->getMessage()), null, $e);
        }

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException(sprintf(
                'The response status code is not 200 (got %s)',
                $response->getStatusCode()
            ));
        }

        $content = $response->getBody()->getContents();
        if (false === strpos($content, '=')) {
            throw new \RuntimeException(sprintf(
                'No or malformed response received from the Buckaroo NVP gateway: %s',
                $content
            ));
        }

        parse_str($content, $responseData);

        return $responseData;
    }

    /**
     * @inheritdoc
     */
    protected function getGatewayUrl(RequestInterface $request, $test = false)
    {
        $url = sprintf('https://%s.buckaroo.nl/nvp/', $test ? 'testcheckout' : 'checkout');

        if ($operation = $request->getOperation()) {
            $url = sprintf('%s?op=%s', $url, $operation);
        }

        return $url;
    }

    /**
     * Log something to the Buckaroo log.
     *
     * @param string $message The message to log.
     * @param mixed  $context The context in which the log was triggered.
     */
    public function log($message, $context)
    {
        $this->logger->debug($message, $context);
    }
}
