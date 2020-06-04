<?php


namespace Mooore\eCurring\Endpoint;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

use Mooore\eCurring\eCurringHttpClient;
use Mooore\eCurring\Resource\AbstractResource;
use Mooore\eCurring\Resource\ResourceFactory;
use Mooore\eCurring\Resource\ResourceFactoryInterface;
use PHPUnit\Framework\TestCase;

abstract class BaseEndpointTest extends TestCase
{

    /**
     * @var ResourceFactoryInterface
     */
    protected $resourceFactory;

    /**
     * @var Client|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $guzzleClient;

    /**
     * @var eCurringHttpClient
     */
    protected $apiClient;

    protected function setUp(): void
    {
        $this->resourceFactory = new ResourceFactory();
        parent::setUp();
    }

    protected function mockApiCall(Request $expectedRequest, Response $response)
    {
        $this->guzzleClient = $this->createMock(Client::class);

        $this->apiClient = new eCurringHttpClient($this->guzzleClient);
        $this->apiClient->setApiKey("unit-test-dummy-api-key");

        $this->guzzleClient
            ->expects($this->once())
            ->method('send')
            ->with($this->isInstanceOf(Request::class))
            ->willReturnCallback(function (Request $request) use ($expectedRequest, $response) {
                $this->assertEquals($expectedRequest->getMethod(), $request->getMethod(),
                    "HTTP method must be identical");

                $this->assertEquals(
                    $expectedRequest->getUri()->getPath(),
                    $request->getUri()->getPath(),
                    "URI path must be identical"
                );

                $this->assertEquals(
                    $expectedRequest->getUri()->getQuery(),
                    $request->getUri()->getQuery(),
                    'Query string parameters must be identical'
                );

                $requestBody = $request->getBody()->getContents();
                $expectedBody = $expectedRequest->getBody()->getContents();

                if (strlen($expectedBody) > 0 && strlen($requestBody) > 0) {
                    $this->assertJsonStringEqualsJsonString(
                        $expectedBody,
                        $requestBody,
                        "HTTP body must be identical"
                    );
                }

                return $response;
            });
    }

    protected function copy($array, $object)
    {
        // Unit test should be isolated, therefore we do not reuse the ResourceFactory::createFromApiResult
        foreach ($array as $property => $value) {
            $object->$property = $value;
        }

        return $object;
    }
}