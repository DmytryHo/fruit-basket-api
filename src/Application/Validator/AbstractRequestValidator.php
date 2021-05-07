<?php

declare(strict_types=1);

namespace App\Application\Validator;

abstract class AbstractRequestValidator
{
    protected function validateBodyForEmptiness(array $rawBody): void
    {
        if (empty($rawBody)) {
            throw new \InvalidArgumentException(message: 'Request body is empty.');
        }
    }

    protected function validateAllowableFields(array $values, array $allowedFields)
    {
        foreach ($values as $fieldName => $value) {
            if (!in_array($fieldName, $allowedFields)) {
                throw new \InvalidArgumentException(message: "Unknown request field '$fieldName'");
            }
        }
    }

    protected function validateRequiredFieldForExistence(array $rawBody, string $fieldName): void
    {
        if (!isset($rawBody[$fieldName])) {
            throw new \InvalidArgumentException(message: "'$fieldName' field is required");
        }
    }

    protected function validateStringField(array $rawBody, string $fieldName)
    {
        if (isset($rawBody[$fieldName]) && !is_string($rawBody[$fieldName])) {
            throw new \InvalidArgumentException(message: "'$fieldName' field must be a string");
        }
    }

    protected function validatePositiveRealNumberField(array $rawBody, string $fieldName)
    {
        if (isset($rawBody[$fieldName]) && (!is_numeric($rawBody[$fieldName]) || $rawBody[$fieldName] <= 0)) {
            throw new \InvalidArgumentException(message: "'$fieldName' must be a number greater than zero");
        }
    }
}
