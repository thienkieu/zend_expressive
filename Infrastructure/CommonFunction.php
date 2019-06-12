<?php 

declare(strict_types=1);

namespace Infrastructure;

use Zend\Diactoros\Response\JsonResponse;
use Zend\Log\Logger;

class CommonFunction
{
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
        if (!is_int($pageNumber)) {
            $pageNumber = $defaultValue;
        }

        return $pageNumber;
    }

    public static function getItemPerPage($data, $defaultValue) {
        $itemPerPage = isset($data['itemPerPage']) ? $data['itemPerPage'] : $defaultValue;
        if (!is_int($itemPerPage)) {
            $itemPerPage = $defaultValue;
        }

        return $itemPerPage;
    }

    public static function getValue($data, $fieldName, $defaultValue) {
        if (is_array($data)) {
            return isset($data[$fieldName]) ? $data[$fieldName] : $defaultValue;
        }

        if (property_exists($data, $fieldName)) return $data->{$fieldName};

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
        for($i = 0; $i < $length ; $i++) {
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