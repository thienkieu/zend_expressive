<?php 

declare(strict_types=1);

namespace Infrastructure\Validator;

use Zend\Validator\AbstractValidator;

class RequireField extends AbstractValidator {
    protected $options = [];

    protected $emptyMessage = 'Field \'%field%\' can not empty';
    protected $messageKey;
    protected $translateObject;

    /**
     * Class constructor.
     */
    public function __construct($translate, $options = [])
    {   
        parent::__construct($options);
        $this->translateObject = $translate;  
    }

    /**
     * Returns true if and only if the string length of $value is at least the min option and
     * no greater than the max option (when the max option is not null).
     *
     * @param  string $value
     * @return bool
     */
    public function isValid($objectValue)
    {
        $isSuccess = true;
        foreach($this->options as $key => $value) {
            $fields = explode('=>',$value);
            $ret = $this->checkRequiredProperty($objectValue, $fields);
            if (!$ret) $isSuccess = false;
        }

        return $isSuccess;
    }

    protected function error($key, $value = null) {
        $message = $this->translateObject->translate($this->emptyMessage, ['%field%' => $key]) ;;
        $this->abstractOptions['messages'][$key] = $message;
    }

    protected function checkRequiredProperty($object, $fields) {
        if (empty($fields) ) return true;
        $field = array_shift($fields);
        $field = \explode(':', $field);
        
        $type = 'string';
        if (count($field) > 1) {
            $type = $field[1];
        }
        $field = $field[0];
        

        $values = null;        
        if (property_exists($object, $field)) {
            $values = $object->{$field};          
        }
        
        $isSuccess = true;
        if($type === 'int') {
            $isNumber  = is_numeric($values);
            if (empty($isNumber)) {
                $this->error($field);
                $isSuccess = false;
                return $isSuccess;
            } 

            return true;
        }
        
        if (empty($values) && $values !== false ) {
            $this->error($field);
            $isSuccess = false;
        }

        if (is_array($values)) {
            foreach($values as $key => $value) {
                $ret = $this->checkRequiredProperty($value, $fields);
                if (!$ret) $isSuccess = false;
            }
        }

        if (is_object($values)) {
            $ret = $this->checkRequiredProperty($values, $fields);
            if (!$ret) $isSuccess = false;
        }

        return $isSuccess;
    }
}