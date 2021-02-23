<?php

namespace SimpleElasticsearch;

use SimpleElasticsearch\BaseElasticsearch;

class SimpleElasticsearch extends BaseElasticsearch
{
    public $elasticHost;

    /**
     * is connected
     * @return bool
     */
    public function isConnected(): bool
    {
        $result = $this->sendRequest(
            'GET',
            $this->elasticHost,
            '/'
        );
        if (is_array($result) &&
            isset($result['error_code'])
        ) {
            return false;
        }
        return true;
    }
    
    /**
     * method sql
     * execute sql on elasticsearch
     * @param string $query
     * @return mixed
     */
    public function sql(
        string $query
    ) {
        $body = [
            'query' => $query,
            'fetch_size' => 25,
        ];
        $result = $this->sendRequest(
            'POST',
            $this->elasticHost,
            '_sql?format=json',
            $body
        );
        return $this->decodeResponse(
            $result
        );
    }

    /**
     * method sqlCursor
     * continue executing sql on elasticsearch based on cursor
     * @param string $query
     * @return mixed
     */
    public function sqlCursor(
        string $cursor
    ) {
        $body = [
            'cursor' => $cursor,
        ];
        $result = $this->sendRequest(
            'POST',
            $this->elasticHost,
            '_sql?format=json',
            $body
        );
        return $this->decodeResponse(
            $result
        );
    }

    /**
     * method translate
     * translate sql to dsl
     * @param string $query
     * @return mixed
     */
    public function translate(
        string $query
    ) {
        $body = [
            'query' => $query,
        ];
        $result = $this->sendRequest(
            'POST',
            $this->elasticHost,
            '/_sql/translate',
            $body
        );
        return $this->decodeResponse(
            $result
        );
    }

    /**
     * method deleteIndex
     * delete an index from elasticsearch
     * @param $indexName
     * @return mixed
     */
    public function deleteIndex(
        string $indexName
    ) {
        $result = $this->sendRequest(
            'DELETE',
            $this->elasticHost,
            '/' . $indexName
        );
        return $this->decodeResponse(
            $result
        );
    }

    /**
     * method listDocuments
     * list documents
     * @param string $indexName
     * @param int $page
     * @return mixed
     */
    public function listDocuments(
        string $indexName,
        int $page = 1
    ) {
        $params = [
            'size' => 25,
            'from' => 0,
        ];
        if ($page > 1) {
            $params['from'] = $this->calculateFrom(
                $page
            );
        }
        $result = $this->sendRequest(
            'POST',
            $this->elasticHost,
            '/' . $indexName . '/_search',
            $params
        );
        return $this->decodeResponse(
            $result
        );
    }

    /**
     * method searchDocuments
     * seach documents
     * @param string $indexName
     * @param array $query
     * @param array $params
     * @return mixed
     */
    public function searchDocuments(
        string $indexName,
        array $query,
        array $params = []
    ) {
        $defaultParams = [
            'size' => 25,
            'from' => 0,
            'query' => $query,
        ];
        $resultParams = array_merge(
            $defaultParams,
            $params
        );
        $result = $this->sendRequest(
            'POST',
            $this->elasticHost,
            '/' . $indexName . '/_search',
            $resultParams
        );
        return $this->decodeResponse(
            $result
        );
    }

    /**
     * method aggregateDocuments
     * return aggregations from documents
     * @param string $indexName
     * @param array $aggregations
     * @param array $query
     * @param array $params
     * @return mixed
     */
    public function aggregateDocuments(
        string $indexName,
        array $aggregations,
        array $query = [],
        array $params = []
    ) {
        $defaultParams = [
            'size' => 0,
            'aggregations' => $aggregations,
        ];
        if (!empty($query)) {
            $defaultParams['query'] = $query;
        }
        $resultParams = array_merge(
            $defaultParams,
            $params
        );
        $result = $this->sendRequest(
            'POST',
            $this->elasticHost,
            '/' . $indexName . '/_search',
            $resultParams
        );
        return $this->decodeResponse(
            $result
        );
    }

    /**
     * method postDocument
     * post a document
     * @param string $indexName
     * @param array $data
     * @return mixed
     */
    public function postDocument(
        string $indexName,
        array $data,
        ?string $id = null
    ) {
        $uri = '/' . $indexName . '/_doc';
        if (!empty($id)) {
            $uri .= '/' . $id;
        }

        $result = $this->sendRequest(
            'POST',
            $this->elasticHost,
            $uri,
            $data
        );
        return $this->decodeResponse(
            $result
        );
    }

    /**
     * method getDocument
     * get a document
     * @param string $indexName
     * @param string $id
     * @return mixed
     */
    public function getDocument(
        string $indexName,
        string $id
    ) {
        $uri = '/' . $indexName . '/_doc/' . $id;

        $result = $this->sendRequest(
            'GET',
            $this->elasticHost,
            $uri
        );
        return $this->decodeResponse(
            $result
        );
    }

    /**
     * method deleteDocument
     * delete a document from elasticsearch
     * @param $indexName
     * @return mixed
     */
    public function deleteDocument(
        string $indexName,
        string $id
    ) {
        $uri = '/' . $indexName . '/_doc/' . $id;

        $result = $this->sendRequest(
            'DELETE',
            $this->elasticHost,
            $uri
        );
        return $this->decodeResponse(
            $result
        );
    }

    /**
     * method putIndex
     * create an index in elasticsearch
     * @param string $indexName
     * @return mixed
     */
    public function putIndex(
        string $indexName
    ) {
        $result = $this->sendRequest(
            'PUT',
            $this->elasticHost,
            '/' . $indexName
        );
        return $this->decodeResponse(
            $result
        );
    }

    /**
     * method putMapping
     * put a mapping to elasticsearch
     * @param string $indexName
     * @param array $mapping
     * @return mixed
     */
    public function putMapping(
        string $indexName,
        array $mapping
    ) {
        $result = $this->sendRequest(
            'PUT',
            $this->elasticHost,
            '/' . $indexName . '/_mapping',
            $mapping
        );
        return $this->decodeResponse(
            $result
        );
    }

    /**
     * method putTemplate
     * put a template to elasticsearch
     * @param string $name
     * @param array $template
     * @return mixed
     */
    public function putTemplate(
        string $name,
        array $template
    ) {
        $result = $this->sendRequest(
            'PUT',
            $this->elasticHost,
            '/_template/' . $name,
            $template
        );
        return $this->decodeResponse(
            $result
        );
    }

    /**
     * method getIndex
     * get an index
     * @param $indexName
     * @return mixed
     */
    public function getIndex(
        string $indexName
    ) {
        $result = $this->sendRequest(
            'GET',
            $this->elasticHost,
            '/' . $indexName
        );
        return $this->decodeResponse(
            $result
        );
    }

    /**
     * method getMapping
     * get a mapping
     * @param $indexName
     * @return mixed
     */
    public function getMapping(
        string $indexName
    ) {
        $result = $this->sendRequest(
            'GET',
            $this->elasticHost,
            '/' . $indexName . '/_mapping'
        );
        return $this->decodeResponse(
            $result
        );
    }

    /**
     * method getTemplate
     * get a template
     * @param $name
     * @return mixed
     */
    public function getTemplate(
        string $name
    ) {
        $result = $this->sendRequest(
            'GET',
            $this->elasticHost,
            '/_template/' . $name
        );
        return $this->decodeResponse(
            $result
        );
    }

    /**
     * method deleteTemplate
     * get a template
     * @param $name
     * @return mixed
     */
    public function deleteTemplate(
        string $name
    ) {
        $result = $this->sendRequest(
            'DELETE',
            $this->elasticHost,
            '/_template/' . $name
        );
        return $this->decodeResponse(
            $result
        );
    }
}
