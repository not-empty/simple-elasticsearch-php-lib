<?php

require_once __DIR__ . '/../vendor/autoload.php';

use SimpleElasticsearch\SimpleElasticsearch;

$host = 'http://localhost:9200/';
$elastic = new SimpleElasticsearch($host);
$elastic->setConnectionOptions([
    'connect_timeout' => 5,
    'timeout' => 5,
]);

$documentName = 'document';
$indexName = 'test';
$id = '01HDRQRB0VPDDB9HWHX3MGY6XG';
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
$data = [
    'name' => 'user',
    'email' => 'test@test.com',
    'gender' => 0,
];
$dataTemplate = [
    'name' => 'document1',
    'created' => date('Y-m-d H:i:s'),
];
$query = "SELECT * FROM test WHERE email LIKE '%test@test.com' ORDER BY email DESC";
$dslQuery =  [
    'term' => [
        'email' => [
            'value' => 'test@test.com',
            'boost' => 1,
        ],
    ],
];

$dslQueryAggregate =  [
    'wildcard' => [
        'email' => [
            'wildcard' => '*1-test@test.com',
            'boost' => 1,
        ],
    ],
];

$dslAgregate = [
    'genders' => [
        'terms' => [
            'field' => 'gender',
        ]
    ]
];

print_r('Is connected');
echo PHP_EOL;
$isConnected = $elastic->isConnected();
$response = 'Elastic is connected';
if (!$isConnected) {
    $response = 'Elastic is NOT connected';
}
print_r($response);
echo PHP_EOL;
print_r('===================================================');
echo PHP_EOL;

print_r('Put index');
echo PHP_EOL;
$index = $elastic->putIndex(
    $indexName
);
print_r($index);
print_r('===================================================');
echo PHP_EOL;

print_r('Put mapping');
echo PHP_EOL;
$newMapping = $elastic->putMapping(
    $indexName,
    $mapping
);
print_r($newMapping);
print_r('===================================================');
echo PHP_EOL;

print_r('Put template');
echo PHP_EOL;
$newTemplate = $elastic->putTemplate(
    $documentName,
    $template
);
print_r($newTemplate);
print_r('===================================================');
echo PHP_EOL;

print_r('Get index');
echo PHP_EOL;
$getIndex = $elastic->getIndex(
    $indexName
);
print_r($getIndex);
print_r('===================================================');
echo PHP_EOL;

print_r('Get mapping');
echo PHP_EOL;
$getMapping = $elastic->getMapping(
    $indexName
);
print_r($getMapping);
print_r('===================================================');
echo PHP_EOL;

print_r('Get template');
echo PHP_EOL;
$getTemplate = $elastic->getTemplate(
    $documentName
);
print_r($getTemplate);
print_r('===================================================');
echo PHP_EOL;

print_r('Post documents with template');
echo PHP_EOL;
$postDocumentTemplate = $elastic->postDocument(
    $documentName,
    $dataTemplate
);
print_r($postDocumentTemplate);
print_r('===================================================');
echo PHP_EOL;

print_r('Post documents passing id');
echo PHP_EOL;
$postDocument = $elastic->postDocument(
    $indexName,
    $data,
    $id
);
print_r($postDocument);
print_r('===================================================');
echo PHP_EOL;

print_r('get document by id');
echo PHP_EOL;
$getDocument = $elastic->getDocument(
    $indexName,
    $id
);
print_r($getDocument);
print_r('===================================================');
echo PHP_EOL;

print_r('Post documents');
echo PHP_EOL;
for ($i = 0; $i < 60; $i++) {
    $data['name'] = 'user ' . $i;
    $data['email'] = $i . '-test@test.com';
    $data['gender'] = rand(0, 1);
    $postDocument = $elastic->postDocument(
        $indexName,
        $data,
        $i
    );
}
print_r('60 documents added') . PHP_EOL;
print_r('===================================================');
echo PHP_EOL;

sleep(1);

print_r('Delete document'); 
echo PHP_EOL;
$deleteDocument = $elastic->deleteDocument(
    $indexName,
    $id
);
print_r($deleteDocument);
print_r('===================================================');
echo PHP_EOL;

print_r('Search documents');
echo PHP_EOL;
$searchDocuments = $elastic->searchDocuments(
    $indexName,
    $dslQuery
);
print_r($searchDocuments);
print_r('===================================================');
echo PHP_EOL;

print_r('Aggregate documents');
echo PHP_EOL;
$searchDocuments = $elastic->aggregateDocuments(
    $indexName,
    $dslAgregate,
    $dslQueryAggregate
);
print_r($searchDocuments);
print_r('===================================================');
echo PHP_EOL;

print_r('Lits documents');
echo PHP_EOL;
$listDocuments = $elastic->listDocuments(
    $indexName
);
print_r($listDocuments);
print_r('===================================================');
echo PHP_EOL;

print_r('Lits documents paginated');
echo PHP_EOL;
for ($page = 2; $page <= 3; $page++) {
    $listDocumentsPaginated = $elastic->listDocuments(
        $indexName,
        $page
    );
    print_r($listDocumentsPaginated);
}
print_r('===================================================');
echo PHP_EOL;

print_r('sql');
echo PHP_EOL;
$sql = $elastic->sql(
    $query,
    'JSON'
);
print_r($sql);
print_r('===================================================');
echo PHP_EOL;

print_r('sql cursor');
echo PHP_EOL;
for ($i = 0; $i < 3; $i++) {
    if (isset($sql['cursor']) && !empty($sql['cursor'])) {
        $sql = $elastic->sqlCursor(
            $sql['cursor']
        );
        print_r($sql);
    }
}
print_r('===================================================');
echo PHP_EOL;

print_r('translate');
echo PHP_EOL;
$translate = $elastic->translate(
    $query
);
print_r($translate);
print_r('===================================================');
echo PHP_EOL;

print_r('Delete template'); 
echo PHP_EOL;
$deleteTemplate = $elastic->deleteTemplate(
    $documentName
);
print_r($deleteTemplate);
print_r('===================================================');
echo PHP_EOL;

print_r('Delete index');
echo PHP_EOL;
$deleteIndex = $elastic->deleteIndex(
    $indexName
);
print_r($deleteIndex);
print_r('===================================================');
echo PHP_EOL;
