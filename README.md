# Simple Elasticsearch PHP

[![Latest Version](https://img.shields.io/github/v/release/not-empty/simple-elasticsearch-php-lib.svg?style=flat-square)](https://github.com/not-empty/simple-elasticsearch-php-lib/releases)
[![codecov](https://codecov.io/gh/not-empty/simple-elasticsearch-php-lib/graph/badge.svg?token=AEMV163UW6)](https://codecov.io/gh/not-empty/simple-elasticsearch-php-lib)
[![CI Build](https://img.shields.io/github/actions/workflow/status/not-empty/simple-elasticsearch-php-lib/php.yml)](https://github.com/not-empty/simple-elasticsearch-php-lib/actions/workflows/php.yml)
[![Downloads Old](https://img.shields.io/packagist/dt/kiwfy/simple-elasticsearch-php?logo=old&label=downloads%20legacy)](https://packagist.org/packages/kiwfy/simple-elasticsearch-php)
[![Downloads](https://img.shields.io/packagist/dt/not-empty/simple-elasticsearch-php-lib?logo=old&label=downloads)](https://packagist.org/packages/not-empty/simple-elasticsearch-php-lib)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square)](http://makeapullrequest.com)
[![Packagist License (custom server)](https://img.shields.io/packagist/l/not-empty/simple-elasticsearch-php-lib)](https://github.com/not-empty/simple-elasticsearch-php-lib/blob/master/LICENSE)


PHP library to connect to and use Elasticsearch in a simple way.

### Installation

[Release 6.0.0](https://github.com/not-empty/simple-elasticsearch-php-lib/releases/tag/6.0.0) Requires [PHP](https://php.net) 8.2

[Release 5.0.0](https://github.com/not-empty/simple-elasticsearch-php-lib/releases/tag/5.0.0) Requires [PHP](https://php.net) 8.1

[Release 4.0.0](https://github.com/not-empty/simple-elasticsearch-php-lib/releases/tag/4.0.0) Requires [PHP](https://php.net) 7.4

[Release 3.0.0](https://github.com/not-empty/simple-elasticsearch-php-lib/releases/tag/3.0.0) Requires [PHP](https://php.net) 7.3

[Release 2.0.0](https://github.com/not-empty/simple-elasticsearch-php-lib/releases/tag/2.0.0) Requires [PHP](https://php.net) 7.2

[Release 1.0.0](https://github.com/not-empty/simple-elasticsearch-php-lib/releases/tag/1.0.0) Requires [PHP](https://php.net) 7.1

The recommended way to install is through [Composer](https://getcomposer.org/).

```sh
composer require not-empty/simple-elasticsearch-php-lib
```

### Usage

Setting up connection

```php
use SimpleElasticsearch\SimpleElasticsearch;
$host = 'http://localhost:9200/';
$elastic = new SimpleElasticsearch($host);
$elastic->setConnectionOptions([
    'connect_timeout' => 5,
    'timeout' => 5,
]);
```

Checking if connection is available

```php
...
$isConnected = $elastic->isConnected();
var_dump($isConnected);
```

Putting an index

```php
...
$indexName = 'test';
$index = $elastic->putIndex(
    $indexName
);
var_dump($index);
```

Putting a mapping

```php
...
$indexName = 'test';
$mapping = [
    'properties' => [
        'name' => [
            'type' => 'keyword',
        ],
        'email' => [
            'type' => 'keyword',
        ],
        'gender' => [
            'type' => 'byte',
        ]
    ]
];
$newMapping = $elastic->putMapping(
    $indexName,
    $mapping
);
var_dump($newMapping);
```

Putting a template

```php
...
$documentName = 'document';
$template = [
    'index_patterns' => [
        'document*'
    ],
    'mappings' => [
        '_source' => [
            'enabled' => true,
        ],
        'properties' => [
            'name' => [
                'type' => 'keyword',
            ],
            'created' => [
                'type'=> 'date',
                'format' => 'yyyy-MM-dd HH:mm:ss',
            ],
        ]
    ]
];
$newTemplate = $elastic->putTemplate(
    $documentName,
    $template
);
var_dump($newTemplate);
```

Getting an index

```php
...
$indexName = 'test';
$getIndex = $elastic->getIndex(
    $indexName
);
var_dump($getIndex);
```

Getting a mapping

```php
...
$indexName = 'test';
$getMapping = $elastic->getMapping(
    $indexName
);
var_dump($getMapping);
```

Getting a template

```php
...
$documentName = 'document';
$getTemplate = $elastic->getTemplate(
    $documentName
);
var_dump($getTemplate);
```

Posting a document with template

```php
...
$documentName = 'document';
$dataTemplate = [
    'name' => 'document1',
    'created' => date('Y-m-d H:i:s'),
];
$postDocumentTemplate = $elastic->postDocument(
    $documentName,
    $dataTemplate
);
var_dump($postDocumentTemplate);
```

Posting a document passing the id

```php
...
$documentName = 'document';
$indexName = 'test';
$data = [
    'name' => 'user',
    'email' => 'test@test.com',
    'gender' => 0,
];
$id = '01HDRQRB0VPDDB9HWHX3MGY6XG';
$postDocument = $elastic->postDocument(
    $indexName,
    $data,
    $id
);
var_dump($postDocument);
```

Getting a document by his id

```php
...
$indexName = 'test';
$id = '01HDRQRB0VPDDB9HWHX3MGY6XG';
$getDocument = $elastic->getDocument(
    $indexName,
    $id
);
var_dump($getDocument);
```

Deleting a document by his id

```php
...
$indexName = 'test';
$id = '01HDRQRB0VPDDB9HWHX3MGY6XG';
$deleteDocument = $elastic->deleteDocument(
    $indexName,
    $id
);
var_dump($deleteDocument);
```

Searching documents

```php
...
$indexName = 'test';
$dslQuery =  [
    'term' => [
        'email' => [
            'value' => 'test@test.com',
            'boost' => 1,
        ],
    ],
];
$searchDocuments = $elastic->searchDocuments(
    $indexName,
    $dslQuery
);
var_dump($searchDocuments);
```

Listing documents

```php
...
$indexName = 'test';
$listDocuments = $elastic->listDocuments(
    $indexName
);
var_dump($listDocuments);
```

Listing documents paginated

```php
...
$indexName = 'test';
$page = 2;
$listDocumentsPaginated = $elastic->listDocuments(
    $indexName,
    $page
);
var_dump($listDocumentsPaginated);
```

Executing 'SQL' querys

```php
...
$query = "SELECT * FROM test WHERE email LIKE '%test@test.com' ORDER BY email DESC";
$sqlResponse = $elastic->sql(
    $query
);
var_dump($sqlResponse);
```

Executing 'SQL' querys with cursor to paginate

```php
...
// var $sql has data returned from previous query with the cursor
$sqlCursorResponse = $elastic->sqlCursor(
    $sql['cursor']
);
var_dump($sqlCursorResponse);
```

Translating 'SQL' query to 'DSL' query

```php
...
$query = "SELECT * FROM test WHERE email LIKE '%test@test.com' ORDER BY email DESC";
$translate = $elastic->translate(
    $query
);
var_dump($translate);
```

Deleting template

```php
...
$documentName = 'document';
$deleteTemplate = $elastic->deleteTemplate(
    $documentName
);
var_dump($deleteTemplate);
```

Deleting index

```php
...
$indexName = 'test';
$deleteIndex = $elastic->deleteIndex(
    $indexName
);
var_dump($deleteIndex);
```

Aggregating documents

```php
...
$indexName = 'test';
$dslAgregate = [
    'genders' => [
        'terms' => [
            'field' => 'gender',
        ]
    ]
];
$dslQueryAggregate =  [
    'wildcard' => [
        'email' => [
            'wildcard' => '*1-test@test.com',
            'boost' => 1,
        ],
    ],
];
$aggregateDocuments = $elastic->aggregateDocuments(
    $indexName,
    $dslAgregate,
    $dslQueryAggregate
);
var_dump($aggregateDocuments);
```

if you want an environment to run or test it, you can build and install dependences like this

```sh
docker build --build-arg PHP_VERSION=8.2-cli -t not-empty/simple-elasticsearch-php-lib:php82 -f contrib/Dockerfile .
```

Access the container
```sh
docker run -v ${PWD}/:/var/www/html -it not-empty/simple-elasticsearch-php-lib:php82 bash
```

Verify if all dependencies is installed
```sh
composer install --no-dev --prefer-dist
```

and run
```sh
php sample/elastic-sample.php
```

### Development

Want to contribute? Great!

The project using a simple code.
Make a change in your file and be careful with your updates!
**Any new code will only be accepted with all validations.**

To ensure that the entire project is fine:

First you need to building a correct environment to install all dependences

```sh
docker build --build-arg PHP_VERSION=8.2-cli -t not-empty/simple-elasticsearch-php-lib:php82 -f contrib/Dockerfile .
```

Access the container
```sh
docker run -v ${PWD}/:/var/www/html -it not-empty/simple-elasticsearch-php-lib:php82 bash
```

Install all dependences
```sh
composer install --dev --prefer-dist
```

Run all validations
```sh
composer check
```

**Not Empty Foundation - Free codes, full minds**
