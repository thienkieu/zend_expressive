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
    ],

    'convertorDTOAdapters' => [
        \Test\Convertor\Adapter\DTOs\ToListeningDTOAdapter::class,
        \Test\Convertor\Adapter\DTOs\ToReadingDTOAdapter::class,
        \Test\Convertor\Adapter\DTOs\ToWritingDTOAdapter::class,
        \Test\Convertor\Adapter\DTOs\ToSourceDTOAdapter::class,
        \Test\Convertor\Adapter\DTOs\Test\ToTestWithSectionDTOAdapter::class,
        \Test\Convertor\Adapter\DTOs\Test\ToSectionDTOAdapter::class,
        \Test\Convertor\Adapter\DTOs\Test\ToQuestionDTOAdapter::class,  
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
    ],

    'validatorRequestAdapters' => [
        \Test\Validator\CreateReadingSectionValidatorAdapter::class,
        \Test\Validator\CreateSectionValidatorAdapter::class,
        \App\Validator\VerifyValidatorAdapter::class,
        \Test\Validator\CreateTestWithSectionValidatorAdapter::class,
    ],
];
