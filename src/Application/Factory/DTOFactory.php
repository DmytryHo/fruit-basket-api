<?php

declare(strict_types=1);

namespace App\Application\Factory;

use App\Application\DTO\BasketDTO;
use App\Application\DTO\BasketItemDTO;
use App\Application\DTO\BasketUpdateDTO;
use App\Domain\BasketDTOInterface;
use App\Domain\BasketItemDTOInterface;
use App\Domain\BasketUpdateDTOInterface;
use Symfony\Component\HttpFoundation\Request;

class DTOFactory
{
    public function createBasketDTOFromRequest(Request $request): BasketDTOInterface
    {
        $rawRequest = $request->toArray();

        return new BasketDTO(
            name: $rawRequest['name'],
            maxCapacity: $rawRequest['maxCapacity']
        );
    }

    public function createBasketUpdateDTOFromRequest(Request $request): BasketUpdateDTOInterface
    {
        $rawRequest = $request->toArray();

        return new BasketUpdateDTO(
            name: $rawRequest['name'],
            maxCapacity: $rawRequest['maxCapacity']
        );
    }

    /** @return BasketItemDTOInterface[] */
    public function createBasketItemDTOsFromRequest(Request $request): array
    {
        $result = [];
        foreach ($request->toArray() as $rawItem) {
            $result[] = new BasketItemDTO(
                type: $rawItem['type'],
                weight: $rawItem['weight']
            );
        }

        return $result;
    }
}
