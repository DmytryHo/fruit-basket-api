<?php

declare(strict_types=1);

namespace App\Application\Controller;

use App\Application\Factory\DTOFactory;
use App\Application\Validator\AddItemToBasketRequestValidator;
use App\Domain\BasketItemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FruitBasketItemController extends AbstractController
{
    public function __construct(
        protected BasketItemService $basketItemService,
        protected DTOFactory $DTOFactory,
    ) {
    }

    /** @Route("/baskets/{basketId}/items", name="basket_item_add", methods={"POST"}) */
    public function addItemsToBasket(
        int $basketId,
        Request $request,
        AddItemToBasketRequestValidator $validator
    ): Response {
        try {
            $validator->validate($request);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        try {
            $result = $this->basketItemService->addItemToBasketById(
                $basketId,
                $this->DTOFactory->createBasketItemDTOsFromRequest($request)
            );
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!$result) {
            return new JsonResponse(
                status: Response::HTTP_NOT_FOUND
            );
        }

        return new JsonResponse(
            status: Response::HTTP_OK
        );
    }

    /** @Route("/baskets/{basketId}/items", name="basket_item_remove", methods={"DELETE"}) */
    public function removeItemsFromBasket(
        int $basketId,
    ): Response {
        if (!$this->basketItemService->cleanBasketById($basketId)) {
            return new JsonResponse(
                status: Response::HTTP_NOT_FOUND
            );
        }

        return new JsonResponse(
            status: Response::HTTP_OK
        );
    }
}
