<?php

namespace SimpleElasticsearch;

use Exception;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\ClientException;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use SimpleElasticsearch\BaseElasticsearch;

class BaseElasticsearchTest extends TestCase
{
    /**
     * @covers SimpleElasticsearch\BaseElasticsearch::__construct
     */
    public function testBaseElasticsearchCanBeInstanciated()
    {
        $baseElasticsearch = new BaseElasticsearch();
        $this->assertInstanceOf(BaseElasticsearch::class, $baseElasticsearch);
    }

    /**
     * @covers SimpleElasticsearch\BaseElasticsearch::calculateFrom
     */
    public function testCalculateFromOne()
    {
        $baseElasticsearch = new BaseElasticsearch();
        $result = $baseElasticsearch->calculateFrom(1);
        $this->assertEquals($result, 0);
    }

    /**
     * @covers SimpleElasticsearch\BaseElasticsearch::calculateFrom
     */
    public function testCalculateFromTwo()
    {
        $baseElasticsearch = new BaseElasticsearch();
        $result = $baseElasticsearch->calculateFrom(2);
        $this->assertEquals($result, 25);
    }

    /**
     * @covers SimpleElasticsearch\BaseElasticsearch::decodeResponse
     */
    public function testDecodeResponse()
    {
        $baseElasticsearch = new BaseElasticsearch();
        $result = $baseElasticsearch->decodeResponse([]);
        $this->assertIsArray($result);
    }

    /**
     * @covers SimpleElasticsearch\BaseElasticsearch::decodeResponse
     */
    public function testDecodeResponseJson()
    {
        $baseElasticsearch = new BaseElasticsearch();
        $result = $baseElasticsearch->decodeResponse('{}');
        $this->assertIsArray($result);
    }

    /**
     * @covers SimpleElasticsearch\BaseElasticsearch::setConnectionOptions
     */
    public function testSetConnectionOptions()
    {
        $options = [
            'connect_timeout' => 5,
            'timeout' => 5,
        ];

        $baseElasticsearch = new BaseElasticsearch();
        $result = $baseElasticsearch->setConnectionOptions($options);
        $this->assertEquals($options, $result);
    }

    /**
     * @covers SimpleElasticsearch\BaseElasticsearch::prepareUrl
     */
    public function testPrepareUrl()
    {
        $baseElasticsearch = new BaseElasticsearch();
        $result = $baseElasticsearch->prepareUrl(
            'http://localhost:9200/',
            'teste'
        );
        $this->assertIsString($result);
        $this->assertEquals('http://localhost:9200/teste', $result);
    }

    /**
     * @covers SimpleElasticsearch\BaseElasticsearch::prepareHeader
     */
    public function testPrepareHeader()
    {
        $expected = [
            'headers' => [],
        ];
        $baseElasticsearch = new BaseElasticsearch();
        $result = $baseElasticsearch->prepareHeader(
            []
        );
        $this->assertIsArray($result);
        $this->assertEquals($expected, $result);
    }

    /**
     * @covers SimpleElasticsearch\BaseElasticsearch::prepareBody
     */
    public function testPrepareBody()
    {
        $expected = [
            'json' => [
                'test' => 1,
            ],
        ];
        $baseElasticsearch = new BaseElasticsearch();
        $result = $baseElasticsearch->prepareBody(
            [
                'test' => 1,
            ]
        );
        $this->assertIsArray($result);
        $this->assertEquals($expected, $result);
    }

    /**
     * @covers SimpleElasticsearch\BaseElasticsearch::prepareBody
     */
    public function testPrepareBodyEmpty()
    {
        $expected = [];
        $baseElasticsearch = new BaseElasticsearch();
        $result = $baseElasticsearch->prepareBody(
            []
        );
        $this->assertIsArray($result);
        $this->assertEquals($expected, $result);
    }

    /**
     * @covers SimpleElasticsearch\BaseElasticsearch::sendRequest
     * @covers SimpleElasticsearch\BaseElasticsearch::decodeResponse
     */
    public function testSendRequest()
    {
        $response = [];
        $payload = [
            'headers' => [
                'test' => 1,
            ],
            'json' => [
                'test' => 1,
            ],
            'connect_timeout' => 5,
            'timeout' => 5,
        ];

        $guzzleMock = Mockery::mock(Guzzle::class);
        $guzzleMock->shouldReceive('POST')
            ->with('http://localhost:9200/test', $payload)
            ->once()
            ->andReturnSelf();

        $guzzleMock->shouldReceive('getBody')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf();
        
        $guzzleMock->shouldReceive('getContents')
            ->once()
            ->withAnyArgs()
            ->andReturn($response);
            
        $baseElasticsearch = Mockery::mock(BaseElasticsearch::class)
            ->makePartial();

        $baseElasticsearch->shouldReceive('prepareHeader')
            ->with([ 'test' => 1 ])
            ->once()
            ->andReturn([
                'headers' => [
                    'test' => 1,
                ],
            ]);

        $baseElasticsearch->shouldReceive('prepareBody')
            ->with([ 'test' => 1 ])
            ->once()
            ->andReturn([
                'json' => [
                    'test' => 1,
                ],
            ]);

        $baseElasticsearch->shouldReceive('prepareUrl')
            ->with('http://localhost:9200', 'test')
            ->once()
            ->andReturn('http://localhost:9200/test');

        $baseElasticsearch->setConnectionOptions([
            'connect_timeout' => 5,
            'timeout' => 5,
        ]);

        $baseElasticsearch->shouldReceive('newGuzzle')
            ->once()
            ->andReturn($guzzleMock);

        $result = $baseElasticsearch->sendRequest(
            'POST',
            'http://localhost:9200',
            'test',
            [
                'test' => 1,
            ],
            [
                'test' => 1,
            ]
        );
        $this->assertIsArray($result);
    }

    /**
     * @covers SimpleElasticsearch\BaseElasticsearch::sendRequest
     * @covers SimpleElasticsearch\BaseElasticsearch::prepareHeader
     * @covers SimpleElasticsearch\BaseElasticsearch::prepareBody
     * @covers SimpleElasticsearch\BaseElasticsearch::prepareUrl
     */
    public function testSqlJson()
    {
        $response = '{}';
        $guzzleMock = Mockery::mock(Guzzle::class);
        $guzzleMock->shouldReceive('POST')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf();

        $guzzleMock->shouldReceive('getBody')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf();
        
        $guzzleMock->shouldReceive('getContents')
            ->once()
            ->withAnyArgs()
            ->andReturn($response);
            
        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();

        $simpleElasticsearch->shouldReceive('newGuzzle')
            ->once()
            ->andReturn($guzzleMock);


        $result = $simpleElasticsearch->sql(
            'query'
        );
        $this->assertIsArray($result);
    }
    
    /**
     * @covers SimpleElasticsearch\BaseElasticsearch::sendRequest
     * @covers SimpleElasticsearch\BaseElasticsearch::prepareHeader
     * @covers SimpleElasticsearch\BaseElasticsearch::prepareBody
     * @covers SimpleElasticsearch\BaseElasticsearch::prepareUrl
     */
    public function testSqlGuzzleError()
    {
        $requestInterfaceSpy = Mockery::spy(RequestInterface::class);
        $clientExceptionMock = Mockery::mock(ClientException::class, [
            'teste',
            $requestInterfaceSpy
        ]);
        $guzzleMock = Mockery::mock(Guzzle::class);
        
        $clientExceptionMock->shouldReceive('getResponse')
            ->twice()
            ->withAnyArgs()
            ->andReturnSelf();

        $clientExceptionMock->shouldReceive('getBody')
            ->once()
            ->withAnyArgs()
            ->andReturn('{}');

        $clientExceptionMock->shouldReceive('getStatusCode')
            ->once()
            ->withAnyArgs()
            ->andReturn(500);

        $guzzleMock->shouldReceive('POST')
            ->once()
            ->withAnyArgs()
            ->andThrows($clientExceptionMock);
            
        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();

        $simpleElasticsearch->shouldReceive('newGuzzle')
            ->once()
            ->andReturn($guzzleMock);

        $result = $simpleElasticsearch->sql(
            'query'
        );
        $this->assertIsArray($result);
        $this->assertEquals($result['message'], []);
        $this->assertEquals($result['error_code'], 500);
    }

    /**
     * @covers SimpleElasticsearch\BaseElasticsearch::sendRequest
     * @covers SimpleElasticsearch\BaseElasticsearch::prepareHeader
     * @covers SimpleElasticsearch\BaseElasticsearch::prepareBody
     * @covers SimpleElasticsearch\BaseElasticsearch::prepareUrl
     */
    public function testSqlGenericError()
    {
        $guzzleMock = Mockery::mock(Guzzle::class);

        $guzzleMock->shouldReceive('POST')
            ->once()
            ->withAnyArgs()
            ->andThrows(new Exception());
            
        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();

        $simpleElasticsearch->shouldReceive('newGuzzle')
            ->once()
            ->andReturn($guzzleMock);

        $result = $simpleElasticsearch->sql(
            'query'
        );
        $this->assertIsArray($result);
        $this->assertEquals($result['message'], null);
        $this->assertEquals($result['error_code'], 0);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
