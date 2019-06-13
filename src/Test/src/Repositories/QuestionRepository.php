<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-authentication-oauth2 for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-authentication-oauth2/blob/master/LICENSE.md
 *     New BSD License
 */

declare(strict_types=1);

namespace Test\Repositories;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\Tools\Pagination\Paginator;
use  Doctrine\MongoDB\Query\Expr;
use date;

class QuestionRepository extends DocumentRepository
{
    public function generateRandomQuestion($type, $subType, $numberSubQuestion, $sources) {
        $builder = $this->createQueryBuilder();
        $question = $builder
                    ->selectSlice('subQuestions', $numberSubQuestion) 
                    ->field('type')->equals($type)
                    ->field('subType')->equals($subType)
                    ->field('source')->notIn($sources)
                    ->getQuery()
                    ->getSingleResult();
        
        return $question;
    }
}
