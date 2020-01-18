<?php

use core\Request;

require_once __DIR__ . "/vendor/autoload.php";

$app = new \core\Application();

// Query
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

echo \core\Builder::getValues($app->send_request());
