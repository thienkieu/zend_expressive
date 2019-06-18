<?php

declare(strict_types=1);

use League\OAuth2\Server\Grant;

$dir = realpath('src');

return [
    'convertorDocumentAdapters' => [
        \Test\Convertor\Adapter\Documents\ToListeningDocumentAdapter::class,
        \Test\Convertor\Adapter\Documents\ToReadingDocumentAdapter::class,
        \Test\Convertor\Adapter\Documents\ToWritingDocumentAdapter::class,
        \Test\Convertor\Adapter\Documents\ToTestDocumentAdapter::class,
        \Test\Convertor\Adapter\Documents\ToQuestionDocumentAdapter::class,
        \Test\Convertor\Adapter\Documents\ToSourceDocumentAdapter::class,
        
        \Test\Convertor\Adapter\Documents\ToSectionDocumentAdapter::class,
        \Test\Convertor\Adapter\Documents\Test\ToRandomQuestionDocumentAdapter::class,
        \Test\Convertor\Adapter\Documents\Test\ToQuestionDocumentAdapter::class,

        \Test\Convertor\Adapter\Documents\ToExamDocumentAdapter::class,
        \Test\Convertor\Adapter\Documents\ToCandidateDocumentAdapter::class,
        \Test\Convertor\Adapter\Documents\ToExamResultDocumentAdapter::class,
    ],

    'convertorDTOAdapters' => [
        \Test\Convertor\Adapter\DTOs\ToListeningDTOAdapter::class,
        \Test\Convertor\Adapter\DTOs\ToReadingDTOAdapter::class,
        \Test\Convertor\Adapter\DTOs\ToWritingDTOAdapter::class,
        \Test\Convertor\Adapter\DTOs\ToSourceDTOAdapter::class,
        \Test\Convertor\Adapter\DTOs\Test\ToTestWithSectionDTOAdapter::class,
        \Test\Convertor\Adapter\DTOs\Test\ToSectionDTOAdapter::class,
        \Test\Convertor\Adapter\DTOs\Test\ToQuestionDTOAdapter::class,  
        \Test\Convertor\Adapter\DTOs\Exam\ToExamHasSectionTestDTOAdapter::class,  
        \Test\Convertor\Adapter\DTOs\Exam\ToCandidateDTOAdapter::class,  
        \Test\Convertor\Adapter\DTOs\Exam\ToPinDTOAdapter::class,  
        \Test\Convertor\Adapter\DTOs\Exam\ToPinInfoDTOAdapter::class,  
    ],

    'convertorDocumentToDTOAdapters' => [
        \Test\Convertor\Adapter\DTOs\FromListeningDocumentAdapter::class,
        \Test\Convertor\Adapter\DTOs\FromReadingDocumentAdapter::class,
        \Test\Convertor\Adapter\DTOs\FromWritingDocumentAdapter::class,
        \Test\Convertor\Adapter\DTOs\FromSubQuestionDocumentAdapter::class,
        \Test\Convertor\Adapter\DTOs\FromSourceDocumentAdapter::class,
        
        \Test\Convertor\Adapter\DTOs\FromQuestionDocumentAdapter::class,
        \Test\Convertor\Adapter\DTOs\FromAnswerDocumentAdapter::class,
        \Test\Convertor\Adapter\DTOs\Test\FromQuestionDocumentAdapter::class,
        \Test\Convertor\Adapter\DTOs\Test\FromSectionTestDocumentAdapter::class,
        \Test\Convertor\Adapter\DTOs\Test\FromTestWithSectionDocumentAdapter::class,
        \Test\Convertor\Adapter\DTOs\Test\FromRandomQuestionDocumentAdapter::class,
        \Test\Convertor\Adapter\DTOs\FromCandidateDocumentAdapter::class,
        \Test\Convertor\Adapter\DTOs\FromExamHasSectionTestDocumentAdapter::class,
    ],

    'validatorRequestAdapters' => [
        \Test\Validator\CreateReadingSectionValidatorAdapter::class,        
        \Test\Validator\CreateTestWithSectionValidatorAdapter::class,
        \Test\Validator\EnterPinValidatorAdapter::class,
    ],
];
