<?php

use League\OAuth2\Server\Grant;

return [
    'private_key'    => __DIR__ . '/../../data/oauth/private.key',
    'public_key'     => __DIR__ . '/../../data/oauth/public.key',
    'encryption_key' => require __DIR__ . '/../../data/oauth/encryption.key',
    'access_token_expire'  => 'P1D',
    'refresh_token_expire' => 'P1M',
    'auth_code_expire'     => 'PT10M',
    'pdo' => [
        'dsn'      => '',
        'username' => '',
        'password' => ''
    ],

    // Set value to null to disable a grant
    'grants' => [
        Grant\ClientCredentialsGrant::class => Grant\ClientCredentialsGrant::class,
        Grant\PasswordGrant::class          => Grant\PasswordGrant::class,
        Grant\AuthCodeGrant::class          => Grant\AuthCodeGrant::class,
        Grant\ImplicitGrant::class          => Grant\ImplicitGrant::class,
        Grant\RefreshTokenGrant::class      => Grant\RefreshTokenGrant::class
    ],
];