# Simple Elasticsearch PHP

[![Latest Version](https://img.shields.io/github/v/release/kiwfy/simple-elasticsearch-php.svg?style=flat-square)](https://github.com/kiwfy/simple-elasticsearch-php/releases)
[![codecov](https://codecov.io/gh/kiwfy/simple-elasticsearch-php/branch/master/graph/badge.svg)](https://codecov.io/gh/kiwfy/simple-elasticsearch-php)
[![Build Status](https://img.shields.io/github/workflow/status/kiwfy/simple-elasticsearch-php/CI?label=ci%20build&style=flat-square)](https://github.com/kiwfy/simple-elasticsearch-php/actions?query=workflow%3ACI)
[![Total Downloads](https://img.shields.io/packagist/dt/kiwfy/simple-elasticsearch-php.svg?style=flat-square)](https://packagist.org/packages/kiwfy/simple-elasticsearch-php)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square)](http://makeapullrequest.com)

PHP library to connect to and use Elasticsearch.

### Installation

Requires [PHP](https://php.net) 7.1.

The recommended way to install is through [Composer](https://getcomposer.org/).

```sh
composer require kiwfy/simple-elasticsearch-php
```

### Sample

it's a good idea to look in the sample folder to understand how it works.

First verify if all dependencies is installed (if need anyelse)
```sh
composer install --no-dev --prefer-dist
```

and run
```sh
php sample/SimpleElasticsearchSample.php
```
### Client setting

To create a new ElasticSearch client, you need to set the ElasticSearch host in the class construct method.

```php
$host = 'http://localhost:9200/';
$elastic = new SimpleElasticsearch($host);
```

### Connection options (Guzzle)

It's possible to set optional settings for Guzzle requests, through the "setConnectionOptions" method. This set of options are used by all requests.

```php
$elastic->setConnectionOptions([
    'connect_timeout' => 5,
    'timeout' => 5,
]);
```

### Development

Want to contribute? Great!

The project using a simple code.
Make a change in your file and be careful with your updates!
**Any new code will only be accepted with all viladations.**

To ensure that the entire project is fine:

First install all the dev dependences
```sh
composer install --dev --prefer-dist
```

Second run all validations
```sh
composer check
```

**Kiwfy - Open your code, open your mind!**
