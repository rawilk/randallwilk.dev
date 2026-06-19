<?php

declare(strict_types=1);

/**
 * Some examples of emails that should not pass validation in production.
 * The base `email` validation rule is not enough, as some of these emails
 * would pass that rule alone.
 */
dataset('invalid production emails', [
    // Dns check fails
    'email@email.test', // The `.test` TLD is reserved and typically does not have public DNS records.
    'user@example.invalid', // The `.invalid` TLD is reserved for clearly invalid domains.
    'test@nonexistent-domain-123456789.com', // Any domain that hasn't been registered or lacks DNS records.
    'someone@local.dev', // Commonly used for local development, but will fail a real DNS check in production.

    // rfc fails
    'plainaddress', // No `@` symbol
    '@missinguser.com', // No username before the `@`
    'missingdomain@', // No domain after the `@`

    // Invalid characters/formatting
    'Joe Smith <email@gmail.com>', // Contains a display name, which is not allowed in simple email validation
    'email@example@gmail.com', // Multiple `@` symbols
    'email..email@gmail.com', // Consecutive dots in the username
    '.email@gmail.com', // Username starting with a dot
    'email.@gmail.com', // Username ending with a dot

    // Spaces and illegal symbols
    'email @gmail.com', // Contains a space
    '#@%^%#$@#$@#.com', // Only garbage characters

    // Edge cases
    'user@127.0.0.1',
]);
