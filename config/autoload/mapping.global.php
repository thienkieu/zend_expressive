<?php

declare(strict_types=1);

return [
    'requestToDTO' => [
        'section.create' => \Test\DTOs\SectionDTO::class,
        'test.create'   => \Test\DTOs\Test\BaseTestDTO::class,
    ],
    'validator' => [
        'section.create' => [            
        ]
    ]
];