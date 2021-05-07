<?php

declare(strict_types=1);

namespace App\Application\Validator;

use Symfony\Component\HttpFoundation\Request;

class AddItemToBasketRequestValidator extends AbstractRequestValidator
{
    public function validate(Request $request): void
    {
        $values = $request->toArray();

        $this->validateBodyForEmptiness($values);

        foreach ($values as $fields) {
            $this->validateAllowableFields($fields, ['type', 'weight']);

            $this->validateRequiredFieldForExistence($fields, 'type');
            $this->validateStringField($fields, 'type');

            $this->validateRequiredFieldForExistence($fields, 'weight');
            $this->validatePositiveRealNumberField($fields, 'weight');
        }
    }
}
