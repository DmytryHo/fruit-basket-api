<?php

declare(strict_types=1);

namespace App\Application\Validator;

use Symfony\Component\HttpFoundation\Request;

class UpdateBasketRequestValidator extends AbstractRequestValidator
{
    public function validate(Request $request): void
    {
        $values = $request->toArray();

        $this->validateAllowableFields($values, ['name', 'maxCapacity']);
        $this->validateBodyForEmptiness($values);

        $this->validateStringField($values, 'name');

        $this->validatePositiveRealNumberField($values, 'maxCapacity');
    }
}
