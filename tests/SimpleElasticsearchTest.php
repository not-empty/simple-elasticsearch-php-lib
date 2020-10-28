<?php

namespace SimpleElasticsearch;

use GuzzleHttp\Client as Guzzle;
use Mockery;
use PHPUnit\Framework\TestCase;
use SimpleElasticsearch\SimpleElasticsearch;

class SimpleElasticsearchTest extends TestCase
{
    /**
     * @covers SimpleElasticsearch\SimpleElasticsearch::__construct
     */
    public function testSimpleElasticsearchCanBeInstanciated()
    {
        $SimpleElasticsearch = new SimpleElasticsearch();
        $this->assertInstanceOf(SimpleElasticsearch::class, $SimpleElasticsearch);
    }

    /**
     * @covers SimpleElasticsearch\SimpleElasticsearch::isConnected
     */
    public function testIsConnected()
    {
        $response = [];
        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();
        
        $simpleElasticsearch->shouldReceive('sendRequest')
            ->with('GET', $host, '/')
            ->once()
            ->andReturn($response);

        $result = $simpleElasticsearch->isConnected();
        $this->assertTrue($result);
    }

    /**
     * @covers SimpleElasticsearch\SimpleElasticsearch::isConnected
     */
    public function testIsNotConnected()
    {
        $response = [
            'error_code' => 0
        ];

        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();

        $simpleElasticsearch->shouldReceive('sendRequest')
            ->with('GET', $host, '/')
            ->once()
            ->andReturn($response);

        $result = $simpleElasticsearch->isConnected();
        $this->assertFalse($result);
    }

    /**
     * @covers SimpleElasticsearch\SimpleElasticsearch::sql
     */
    public function testSql()
    {
        $response = [];
        $body = [
            'query' => 'query',
            'fetch_size' => 25,
        ];
        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();

        $simpleElasticsearch->shouldReceive('sendRequest')
            ->with('POST', $host, '_sql?format=json', $body)
            ->once()
            ->andReturn($response);

        $simpleElasticsearch->shouldReceive('decodeResponse')
            ->with($response)
            ->once()
            ->andReturn($response);

        $result = $simpleElasticsearch->sql(
            'query'
        );
        $this->assertIsArray($result);
    }

    /**
     * @covers SimpleElasticsearch\SimpleElasticsearch::sqlCursor
     */
    public function testSqlCursor()
    {
        $response = [];
        $body = [
            'cursor' => 'cursor',
        ];
        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();

        $simpleElasticsearch->shouldReceive('sendRequest')
            ->with('POST', $host, '_sql?format=json', $body)
            ->once()
            ->andReturn($response);
        
        $simpleElasticsearch->shouldReceive('decodeResponse')
            ->with($response)
            ->once()
            ->andReturn($response);

        $result = $simpleElasticsearch->sqlCursor(
            'cursor'
        );
        $this->assertIsArray($result);
    }

    /**
     * @covers SimpleElasticsearch\SimpleElasticsearch::sql
     */
    public function testSqlJson()
    {
        $response = '{}';
        $body = [
            'query' => 'query',
            'fetch_size' => 25,
        ];
        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();

        $simpleElasticsearch->shouldReceive('sendRequest')
            ->with('POST', $host, '_sql?format=json', $body)
            ->once()
            ->andReturn($response);
        
        $simpleElasticsearch->shouldReceive('decodeResponse')
            ->with($response)
            ->once()
            ->andReturn([]);

        $result = $simpleElasticsearch->sql(
            'query'
        );
        $this->assertIsArray($result);
    }

    /**
     * @covers SimpleElasticsearch\SimpleElasticsearch::translate
     */
    public function testTranslate()
    {
        $response = [];
        $body = [
            'query' => 'query',
        ];

        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();
            
        $simpleElasticsearch->shouldReceive('sendRequest')
            ->with('POST', $host, '/_sql/translate', $body)
            ->once()
            ->andReturn($response);
            
        $simpleElasticsearch->shouldReceive('decodeResponse')
            ->with($response)
            ->once()
            ->andReturn($response);

        $result = $simpleElasticsearch->translate(
            'query'
        );
        $this->assertIsArray($result);
    }

    /**
     * @covers SimpleElasticsearch\SimpleElasticsearch::deleteIndex
     */
    public function testDeleteIndex()
    {
        $response = [];
        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();

        $simpleElasticsearch->shouldReceive('sendRequest')
            ->with('DELETE', $host, '/index')
            ->once()
            ->andReturn($response);

        $simpleElasticsearch->shouldReceive('decodeResponse')
            ->with($response)
            ->once()
            ->andReturn($response);

        $result = $simpleElasticsearch->deleteIndex(
            'index'
        );
        $this->assertIsArray($result);
    }

    /**
     * @covers SimpleElasticsearch\SimpleElasticsearch::listDocuments
     */
    public function testListDocuments()
    {
        $response = [];
        $params = [
            'size' => 25,
            'from' => 25,
        ];
        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();

        $simpleElasticsearch->shouldReceive('sendRequest')
            ->with('POST', $host, '/index/_search', $params)
            ->once()
            ->andReturn($response);

        $simpleElasticsearch->shouldReceive('decodeResponse')
            ->with($response)
            ->once()
            ->andReturn($response);

        $result = $simpleElasticsearch->listDocuments(
            'index',
            2
        );
        $this->assertIsArray($result);
    }

    /**
     * @covers SimpleElasticsearch\SimpleElasticsearch::searchDocuments
     */
    public function testSearchDocuments()
    {
        $response = [];
        $query = [
            'query' => 'test',
        ];
        $params = [
            'from' => 25,
            'sort' => 'asc',
        ];
        $resultParams = [
            'size' => 25,
            'from' => 25,
            'query' => $query,
            'sort' => 'asc',
        ];
        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();

        $simpleElasticsearch->shouldReceive('sendRequest')
            ->with('POST', $host, '/index/_search', $resultParams)
            ->once()
            ->andReturn($response);

        $simpleElasticsearch->shouldReceive('decodeResponse')
            ->with($response)
            ->once()
            ->andReturn($response);

        $result = $simpleElasticsearch->searchDocuments(
            'index',
            $query,
            $params
        );
        $this->assertIsArray($result);
    }

    /**
     * @covers SimpleElasticsearch\SimpleElasticsearch::aggregateDocuments
     */
    public function testAggregateDocuments()
    {
        $response = [];
        $aggregations = [
            'test' => 'test',
        ];
        $query = [
            'test' => '123',
        ];
        $params = [
            'size' => 0,
            'aggregations' => $aggregations,
            'query' => $query,
        ];
        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();

        $simpleElasticsearch->shouldReceive('sendRequest')
            ->with('POST', $host, '/index/_search', $params)
            ->once()
            ->andReturn($response);

        $simpleElasticsearch->shouldReceive('decodeResponse')
            ->with($response)
            ->once()
            ->andReturn($response);

        $result = $simpleElasticsearch->aggregateDocuments(
            'index',
            $aggregations,
            $query
        );
        $this->assertIsArray($result);
    }

    /**
     * @covers SimpleElasticsearch\SimpleElasticsearch::postDocument
     */
    public function testPostDocument()
    {
        $response = [];
        $data = [
            'name' => 'test',
        ];
        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();

        $simpleElasticsearch->shouldReceive('sendRequest')
            ->with('POST', $host, '/index/_doc/123', $data)
            ->once()
            ->andReturn($response);

        $simpleElasticsearch->shouldReceive('decodeResponse')
            ->with($response)
            ->once()
            ->andReturn($response);

        $result = $simpleElasticsearch->postDocument(
            'index',
            $data,
            '123'
        );
        $this->assertIsArray($result);
    }

    /**
     * @covers SimpleElasticsearch\SimpleElasticsearch::getDocument
     */
    public function testGetDocument()
    {
        $response = [];
        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();

        $simpleElasticsearch->shouldReceive('sendRequest')
            ->with('GET', $host, '/index/_doc/1')
            ->once()
            ->andReturn($response);

        $simpleElasticsearch->shouldReceive('decodeResponse')
            ->with($response)
            ->once()
            ->andReturn($response);

        $result = $simpleElasticsearch->getDocument(
            'index',
            '1'
        );
        $this->assertIsArray($result);
    }

    /**
     * @covers SimpleElasticsearch\SimpleElasticsearch::deleteDocument
     */
    public function testDeleteDocument()
    {
        $response = [];
        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();

        $simpleElasticsearch->shouldReceive('sendRequest')
            ->with('DELETE', $host, '/index/_doc/1')
            ->once()
            ->andReturn($response);

        $simpleElasticsearch->shouldReceive('decodeResponse')
            ->with($response)
            ->once()
            ->andReturn($response);

        $result = $simpleElasticsearch->deleteDocument(
            'index',
            '1'
        );
        $this->assertIsArray($result);
    }

    /**
     * @covers SimpleElasticsearch\SimpleElasticsearch::putIndex
     */
    public function testPutIndex()
    {
        $response = [];
        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();

        $simpleElasticsearch->shouldReceive('sendRequest')
            ->with('PUT', $host, '/index')
            ->once()
            ->andReturn($response);

        $simpleElasticsearch->shouldReceive('decodeResponse')
            ->with($response)
            ->once()
            ->andReturn($response);

        $result = $simpleElasticsearch->putIndex(
            'index'
        );
        $this->assertIsArray($result);
    }

    /**
     * @covers SimpleElasticsearch\SimpleElasticsearch::putMapping
     */
    public function testPutMapping()
    {
        $response = [];
        $mapping = [
            'test' => '1',
        ];
        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();

        $simpleElasticsearch->shouldReceive('sendRequest')
            ->with('PUT', $host, '/index/_mapping', $mapping)
            ->once()
            ->andReturn($response);

        $simpleElasticsearch->shouldReceive('decodeResponse')
            ->with($response)
            ->once()
            ->andReturn($response);

        $result = $simpleElasticsearch->putMapping(
            'index',
            $mapping
        );
        $this->assertIsArray($result);
    }

    /**
     * @covers SimpleElasticsearch\SimpleElasticsearch::putTemplate
     */
    public function testPutTemplate()
    {
        $response = [];
        $template = [
            'test' => '1',
        ];
        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();

        $simpleElasticsearch->shouldReceive('sendRequest')
            ->with('PUT', $host, '/_template/index', $template)
            ->once()
            ->andReturn($response);

        $simpleElasticsearch->shouldReceive('decodeResponse')
            ->with($response)
            ->once()
            ->andReturn($response);

        $result = $simpleElasticsearch->putTemplate(
            'index',
            $template
        );
        $this->assertIsArray($result);
    }

    /**
     * @covers SimpleElasticsearch\SimpleElasticsearch::getIndex
     */
    public function testGetIndex()
    {
        $response = [];
        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();

        $simpleElasticsearch->shouldReceive('sendRequest')
            ->with('GET', $host, '/index')
            ->once()
            ->andReturn($response);

        $simpleElasticsearch->shouldReceive('decodeResponse')
            ->with($response)
            ->once()
            ->andReturn($response);

        $result = $simpleElasticsearch->getIndex(
            'index'
        );
        $this->assertIsArray($result);
    }

    /**
     * @covers SimpleElasticsearch\SimpleElasticsearch::getMapping
     */
    public function testGetMapping()
    {
        $response = [];
        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();

        $simpleElasticsearch->shouldReceive('sendRequest')
            ->with('GET', $host, '/index/_mapping')
            ->once()
            ->andReturn($response);

        $simpleElasticsearch->shouldReceive('decodeResponse')
            ->with($response)
            ->once()
            ->andReturn($response);

        $result = $simpleElasticsearch->getMapping(
            'index'
        );
        $this->assertIsArray($result);
    }

    /**
     * @covers SimpleElasticsearch\SimpleElasticsearch::getTemplate
     */
    public function testGetTemplate()
    {
        $response = [];
        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();

        $simpleElasticsearch->shouldReceive('sendRequest')
            ->with('GET', $host, '/_template/index')
            ->once()
            ->andReturn($response);

        $simpleElasticsearch->shouldReceive('decodeResponse')
            ->with($response)
            ->once()
            ->andReturn($response);

        $result = $simpleElasticsearch->getTemplate(
            'index'
        );
        $this->assertIsArray($result);
    }

    /**
     * @covers SimpleElasticsearch\SimpleElasticsearch::deleteTemplate
     */
    public function testDeleteTemplate()
    {
        $response = [];
        $host = 'http://localhost:9200/';
        $simpleElasticsearch = Mockery::mock(SimpleElasticsearch::class, [$host])
            ->makePartial();

        $simpleElasticsearch->shouldReceive('sendRequest')
            ->with('DELETE', $host, '/_template/index')
            ->once()
            ->andReturn($response);

        $simpleElasticsearch->shouldReceive('decodeResponse')
            ->with($response)
            ->once()
            ->andReturn($response);

        $result = $simpleElasticsearch->deleteTemplate(
            'index'
        );
        $this->assertIsArray($result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
