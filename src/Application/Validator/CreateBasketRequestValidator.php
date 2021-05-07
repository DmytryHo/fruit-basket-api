<?php

declare(strict_types=1);

namespace App\Application\Validator;

use Symfony\Component\HttpFoundation\Request;

class CreateBasketRequestValidator extends AbstractRequestValidator
{
    public function validate(Request $request): void
    {
        $values = $request->toArray();

        $this->validateAllowableFields($values, ['name', 'maxCapacity']);

        $this->validateRequiredFieldForExistence($values, 'name');
        $this->validateStringField($values, 'name');

        $this->validateRequiredFieldForExistence($values, 'maxCapacity');
        $this->validatePositiveRealNumberField($values, 'maxCapacity');
    }
}
