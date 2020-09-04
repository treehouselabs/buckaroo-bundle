<?php

namespace TreeHouse\BuckarooBundle\Tests\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Mockery as Mock;
use PHPUnit\Framework\TestCase;
use TreeHouse\BuckarooBundle\Client\NvpClient;
use TreeHouse\BuckarooBundle\Request\RequestInterface;
use TreeHouse\BuckarooBundle\Response\ResponseInterface;
use TreeHouse\BuckarooBundle\SignatureGenerator;

class NvpClientTest extends TestCase
{
    /**
     * @test
     */
    public function it_does_not_decode_response_data()
    {
        $request = $this->getRequestMock([]);
        $request->shouldReceive('getResponseClass')->once()->andReturn(MockResponse::class);

        $client = $this->createNvpClient(['foobar' => $encodedValue = 'foo%20bar@example.com']);

        /* @var MockResponse $response */
        $response = $client->send($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame($encodedValue, $response->getFoobar());
    }

    /**
     * @test
     */
    public function it_can_send_a_nvp_request()
    {
        $data = ['fruit' => 'apple'];

        $response = $this->getResponseMock();

        $request = $this->getRequestMock($data);
        $request->shouldReceive('getResponseClass')->once()->andReturn($response);

        $client = $this->createNvpClient();
        $this->assertInstanceOf(ResponseInterface::class, $client->send($request));
    }

    /**
     * @test
     */
    public function it_can_get_the_gateway_url()
    {
        $client = $this->createNvpClient();

        $response = $this->getResponseMock();

        $request = $this->getRequestMock([], 'transactionstatus');
        $request->shouldReceive('getResponseClass')->once()->andReturn(get_class($response));

        $getGatewayUrlMethod = new \ReflectionMethod(get_class($client), 'getGatewayUrl');
        $getGatewayUrlMethod->setAccessible(true);
        $gatewayUrl = $getGatewayUrlMethod->invokeArgs($client, [$request, true]);

        $client->send($request);

        $this->assertSame('https://testcheckout.buckaroo.nl/nvp/?op=transactionstatus', $gatewayUrl, 'The correct gateway URL was fetched.');
    }

    /**
     * @test
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The response status code is not 200 (got 500)
     */
    public function it_can_send_a_nvp_request_which_returns_with_error()
    {
        $data = ['fruit' => 'apple'];

        $request = $this->getRequestMock($data);
        $request->shouldNotReceive('getResponseClass');

        $client = $this->createNvpClient([], 500);
        $client->send($request);
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
        $generator = Mock::mock(SignatureGenerator::class);
        $generator->shouldReceive('generate')->andReturn('1234');

        $query = http_build_query(array_merge(['BRQ_SIGNATURE' => '1234'], $data));

        $guzzle = new Client(['handler' => new MockHandler([new Response($responseStatusCode, [], $query)])]);
        $client = new NvpClient($guzzle, $generator, $websiteKey, true);

        return $client;
    }

    /**
     * @return ResponseInterface
     */
    private function getResponseMock()
    {
        $response = Mock::mock(ResponseInterface::class);
        $response->shouldReceive('create')->andReturn($response); // cant check arguments (not same instance)

        return $response;
    }

    /**
     * @param array  $data
     * @param string $operation
     *
     * @return RequestInterface
     */
    private function getRequestMock(array $data = [], $operation = null)
    {
        $request = Mock::mock(RequestInterface::class);
        $request->shouldReceive('toArray')->once()->andReturn($data);
        $request->shouldReceive('getOperation')->andReturn($operation);

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
        $response->foobar = $data['foobar'];

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
