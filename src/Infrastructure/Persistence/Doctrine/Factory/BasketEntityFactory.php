<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Factory;

use Symfony\Component\HttpFoundation\Request;

class BasketEntityFactory
{
    public function createFromRequest(Request $request)
    {
        $request->toArray();
    }
}
