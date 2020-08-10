<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;
use Zend\Http\Request;
use Zend\Http\Client;
use Zend\Stdlib\Parameters;

use Infrastructure\Interfaces\HandlerInterface;

class TrackingConnectService implements TrackingConnectServiceInterface, HandlerInterface
{
    private $container;
    private $dm;
    private $options;

    public function __construct($container, $options) {
        $this->container = $container;
        $this->options = $options;
        $this->dm = $this->container->get('documentManager');
    }

    public function isHandler($param, $options = []){
        return true;
    }
    
    public function getLatestDisconnect($token) {
        $appConfig = $this->container->get(\Config\AppConstant::AppConfig);
        $trackingConnectConfig = $appConfig[\Config\AppConstant::TRACKING_CONNECT];
        $latestDisconnectURL = $trackingConnectConfig[\Config\AppConstant::LatestDisConnectURL];
        
        $client = new Client();
        $client->setUri($latestDisconnectURL);
        $client->setParameterGet(['token'=>$token]);

        try {
            $response = $client->send();
            if ($response->isSuccess()) {
                $disconnecTime = json_decode($response->getBody());
                //$logger = $this->container->get(\Zend\Log\Logger::class);
                //$logger->info($disconnecTime);
                $latest = $disconnecTime[0];
                return \floor($latest->connectTime/1000);
            } else {
                return time();
            }
        } catch(\Exception $e) {
            return time();
        }
        
    }
    
}
