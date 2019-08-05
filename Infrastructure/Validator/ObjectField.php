<?php 

declare(strict_types=1);

namespace Infrastructure\Validator;

use Zend\Validator\AbstractValidator;

class ObjectField extends AbstractValidator {
    protected $options = [];
    protected $translateObject;
    protected $isIgnoreParentEmpty;
    protected $parentEmptyMessage = "Cannot read value of property %propertyName%";
   
    /**
     * Class constructor.
     */
    public function __construct($translate, $isIgnoreParentEmpty = false, $options = [])
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
        foreach($this->options as $key => $validators) {
            $fields = explode('.', $key);
            $objs = $this->getObjectValidate($objectValue, $fields);
            $this->runValidate($objs, $validators, $key);
        }
        return !count($this->abstractOptions['messages']);
    }

    public function getMessages()
    {
        return $this->abstractOptions['messages'];
    }

    protected function runValidate($obj, $validators) {
        $ret = [];
        if (is_array($obj)) {
            foreach($obj as $item) {
                $r = $this->runValidate($item, $validators);                          
            }
            return;
        }

        if ($this->isIgnoreParentEmpty && $obj->isParent) {
            return;
        }
       
        if ($obj->isParent) {
            $this->abstractOptions['messages'][$obj->fieldName][] = $this->translateObject->translate($this->parentEmptyMessage, ['%propertyName%' => $obj->fieldName]);
            return;
        }

        foreach($validators as $validator) {
            $v = $validator->isValid($obj->value);
            $messages = $validator->getMessages();
            foreach ($messages as $message) {
                $this->abstractOptions['messages'][$obj->fieldName][] = $message;
            }        
        }

        return;
    }

    protected function buildObject($value, $isParent = false, $fieldName) {
        $ret = new \stdClass();
        $ret->isParent = $isParent;
        $ret->value = $value;
        $ret->fieldName = $fieldName;

        return $ret;
    }

    protected function getObjectValidate($object, $fields, $parentField = '') {
        if (!$fields) return $this->buildObject($object, false, $parentField);

        $field = array_shift($fields);
        $fieldPath = $parentField? $parentField.'.'.$field :  $field;
        if (!property_exists($object, $field)) {
            if (!$fields) {
                return $this->buildObject(null, false, $fieldPath); 
            }
            return $this->buildObject(null, true, $fieldPath); 
                     
        }

        $values = $object->{$field}; 
        if (is_array($values)) {
            $ret = [];
            $index = 0;
            foreach($values as $value) {
                $ret[] = $this->getObjectValidate($value, $fields, $fieldPath.'.'.$index); 
                $index ++;               
            }
            return $ret;
        }

        return $this->getObjectValidate($values, $fields, $fieldPath);
    }
}