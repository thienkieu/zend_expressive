<?php
namespace App\Enums;

abstract class ExamType
{
    const Verbal = 'Verbal';
    const Writing = 'Writing';
    const MultipleChoice = 'MultipleChoice';

    public static function isValid($type) {
        if ($type !== ExamType::Verbal && $type !== ExamType::Writing && $type !== ExamType::MultipleChoice){
            return false;
        }

        return true;
    }
    
}
