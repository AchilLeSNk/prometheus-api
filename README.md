# Prometheus API

A simple Object Oriented library for 
[Prometheus API](https://prometheus.io/docs/prometheus/latest/querying/api/)

## Installation
You can simply Download the Release, and to include the autoloader
```php
require_once '/path/to/your-project/vendor/autoload.php';
```

## Configuration
API utilizes the DotEnv PHP library by Vance Lucas. In the root directory of your application will contain a .env file. 

Set your prometheus URL

    PROMETHEUS_URL="https://prometheus.yourdomain.com"

Detect an AJAX request
    
    AJAX_DETECT=0

This determines whether errors should be printed to the screen as part of the output. Default "0".

    SHOW_ERRORS=0
    
Tells whether script error messages should be logged library log. Default "1".

    LOG_ERRORS=1
   
## Basic usage
We need to create application so that we can run it and send the responses back.
```php
$app = new \core\Application();
```
Then We set request parameters 
```php
$app->setParams([
    'endpoint' => Request::QUERY,
]);
```
And We can to send request
```php
$app->send_request();
```

## Examples
The sections below describe the API endpoints for each type of expression query.
### Query
``prometheus.yourdomain.com/api/v1/query?query=function(index{conditions}[offset_modifier])[range_vector_selectors:]``

[Function doc](https://prometheus.io/docs/prometheus/latest/querying/functions/)
```php
$app->setParams([
    'endpoint' => Request::QUERY,
    'func' => [
        'name' => 'irate',
        'offset_modifier' => '5m',
    ],
    'index' => [
        'name' => 'ifHCInOctets',
        'conditions' => [
            'instance' => '127.0.0.1',
            'ifIndex' => 1
        ],
    ],
    'range_vector_selectors' => '1h'
]);
```
Then send request
```php
$res = $app->send_request();
```

### LABELS
```php
$app->setParams([
    'endpoint' => Request::LABELS,
]);
```
Then send request
```php
$res = $app->send_request();
```

### SERIES
```php
$app->setParams([
    'endpoint' => Request::LABELS,
]);
```
Then send request
```php
$res = $app->send_request();
```

## Test
You can use curl and jq as that checks response (JSON)

`curl -g "https://prometheus.yourdomain.com/prometheus-api/index.php" | jq`