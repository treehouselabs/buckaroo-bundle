<?php

namespace TreeHouse\BuckarooBundle\Tests\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use TreeHouse\BuckarooBundle\Client\NvpClient;
use TreeHouse\BuckarooBundle\Request\RequestInterface;
use TreeHouse\BuckarooBundle\Response\ResponseInterface;
use TreeHouse\BuckarooBundle\SignatureGenerator;

class NvpClientTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_send_a_nvp_request()
    {
        $data = ['fruit' => 'apple'];

        $request = $this->getRequestMock($data);
        $request->getResponseClass()->willReturn(MockResponse::class);

        $client = $this->createNvpClient();
        $this->assertInstanceOf(ResponseInterface::class, $client->send($request->reveal()));
    }

    /**
     * @test
     */
    public function it_does_not_decode_response_data()
    {
        $request = $this->getRequestMock([]);
        $request->getResponseClass()->willReturn(MockResponse::class);

        $client = $this->createNvpClient(['foobar' => $encodedValue = 'foo%20bar@example.com']);

        /* @var MockResponse $response */
        $response = $client->send($request->reveal());

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame($encodedValue, $response->getFoobar());
    }

    /**
     * @test
     */
    public function it_can_get_the_gateway_url()
    {
        $client = $this->createNvpClient();

        $request = $this->getRequestMock([], 'transactionstatus');
        $request->getResponseClass()->willReturn(MockResponse::class);

        $getGatewayUrlMethod = new \ReflectionMethod(get_class($client), 'getGatewayUrl');
        $getGatewayUrlMethod->setAccessible(true);
        $gatewayUrl = $getGatewayUrlMethod->invokeArgs($client, [$request->reveal(), true]);

        $client->send($request->reveal());

        $this->assertSame('https://testcheckout.buckaroo.nl/nvp/?op=transactionstatus', $gatewayUrl, 'The correct gateway URL was fetched.');
    }

    /**
     * @test
     */
    public function it_can_send_a_nvp_request_which_returns_with_error()
    {
        $data = ['fruit' => 'apple'];

        $request = $this->getRequestMock($data);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The response status code is not 200 (got 500)');

        $client = $this->createNvpClient([], 500);
        $client->send($request->reveal());
    }

    /**
     * @param array $data
     * @param int   $responseStatusCode
     *
     * @return NvpClient
     */
    private function createNvpClient(array $data = [], $responseStatusCode = 200)
    {
        $websiteKey = 'website-key';

        /** @var SignatureGenerator $generator */
        $generator = $this->prophesize(SignatureGenerator::class);
        $generator->generate(Argument::any())->willReturn('1234');

        $query = http_build_query(array_merge(['BRQ_SIGNATURE' => '1234'], $data));

        $guzzle = new Client(['handler' => new MockHandler([new Response($responseStatusCode, [], $query)])]);
        $client = new NvpClient($guzzle, $generator->reveal(), $websiteKey, true);

        return $client;
    }

    /**
     * @param array  $data
     * @param string $operation
     *
     * @return ObjectProphecy|RequestInterface
     */
    private function getRequestMock(array $data = [], $operation = null)
    {
        /** @var RequestInterface $request */
        $request = $this->prophesize(RequestInterface::class);
        $request->toArray()->willReturn($data);
        $request->getOperation()->willReturn($operation);

        return $request;
    }
}

class MockResponse implements ResponseInterface
{
    /**
     * @var mixed
     */
    private $foobar;

    /**
     * @inheritdoc
     */
    public static function create(array $data)
    {
        $response = new static();
        $response->foobar = $data['foobar'] ?? null;

        return $response;
    }

    /**
     * @return mixed
     */
    public function getFoobar()
    {
        return $this->foobar;
    }
}
