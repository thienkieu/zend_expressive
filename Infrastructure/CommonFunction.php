<?php 

declare(strict_types=1);

namespace Infrastructure;

use Zend\Diactoros\Response\JsonResponse;
use Zend\Log\Logger;

class CommonFunction
{
    public static function getServerHost() {
        $protocal = 'http://';
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
            $protocal = 'https://';
        }

        $port = '';
        if (!in_array($_SERVER['SERVER_PORT'], [80, 443])) {
            $port = ":$_SERVER[SERVER_PORT]";
        }

        return $protocal.$_SERVER['SERVER_NAME']. $port;
    }
    
    public static function buildResponseFormat($status, $messages=[], $data = null) {
        if ($data === null) $data = new \stdClass();
        
        return new JsonResponse([
            'isSuccess' => $status,      
            'messages'  => $messages,
            'data' => $data
        ]);
    }

    public static function getPageNumber($data, $defaultValue) {
        $pageNumber = isset($data['pageNumber']) ? $data['pageNumber'] : $defaultValue;
        if (!intval($pageNumber)) {
            $pageNumber = $defaultValue;
        }

        return $pageNumber;
    }

    public static function getItemPerPage($data, $defaultValue) {
        $itemPerPage = isset($data['itemPerPage']) ? $data['itemPerPage'] : $defaultValue;
        if (!intval($itemPerPage)) {
            $itemPerPage = $defaultValue;
        }

        return $itemPerPage;
    }

    public static function getValue($data, $fieldName, $defaultValue = '') {
        if (is_array($data)) {
            return isset($data[$fieldName]) ? $data[$fieldName] : $defaultValue;
        }

        if (property_exists($data, $fieldName)) {
            $functionName = 'get'.ucfirst($fieldName);
            return $data->$functionName();
        } 

        return $defaultValue;
    }

    public static function parseNoneEmptyValue($data, $fieldName, & $outputValue) {
        if (is_array($data)) {
            $outputValue = isset($data[$fieldName]) ? $data[$fieldName] : '';           
        }

        if (property_exists($data, $fieldName)){
            $outputValue = $data->{$fieldName};
        } 

        if (empty($outputValue)) {
            return false;
        }

        return true;
    }

    public static function generateUniquePin($numberPin, $length = \Config\AppConstant::PinLength) {
        $ret = [];
        for($i = 0; $i < $numberPin ; $i++) {
            $unique = uniqid();
            $ret[] = substr(uniqid(), strlen($unique) - $length, $length);
        }

        return $ret;
        
    }

    public static function convertToDateTimeFormat($date, $format = \Config\AppConstant::DateTimeFormat ) {
        if ($date instanceof \DateTime) {
           return $date->format($format);
        } 
        
        return \DateTime::createFromFormat($format, $date);
    }
}