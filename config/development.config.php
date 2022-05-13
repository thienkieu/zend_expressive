<?php
/**
 * File required to allow enablement of development mode.
 *
 * For use with the zf-development-mode tool.
 *
 * Usage:
 *  $ composer development-disable
 *  $ composer development-enable
 *  $ composer development-status
 *
 * DO NOT MODIFY THIS FILE.
 *
 * Provide your own development-mode settings by editing the file
 * `config/autoload/development.local.php.dist`.
 *
 * Because this file is aggregated last, it simply ensures:
 *
 * - The `debug` flag is _enabled_.
 * - Configuration caching is _disabled_.
 */

declare(strict_types=1);

use Zend\ConfigAggregator\ConfigAggregator;
$dir = realpath('src');

return [
    'debug' => true,
    ConfigAggregator::ENABLE_CACHE => false,
    'nonsqldb' => [
        'mongodb-connection' => 'mongodb://admin:pass@mongo-dev:27017',
        //'mongodb-connection' => 'mongodb://thienkieu:Mlab0958588127@ds253017.mlab.com:53017/onlinetest_demo?retryWrites=false',
        'document-path' => [
            $dir.'/App/src/Documents',
            $dir.'/Test/src/Documents',
            $dir.'/ODMAuth/src/Documents',
        ],
        'proxy-path' =>  $dir.'/../Proxies',
        'hydrators-path' =>  $dir.'/../Hydrators',
        'dbname' => 'onlinetest',
    ],
    'CRM' => [
        'candidates' => 'http://192.168.190.92:8089/Portal/ExcuteByCommand/GetCandiates'
       // 'candidates' => 'http://localhost:12346/Portal/ExcuteByCommand/GetCandiates'
    ],
    
];
