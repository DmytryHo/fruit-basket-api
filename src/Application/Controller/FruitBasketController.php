<?php

declare(strict_types=1);

namespace App\Application\Controller;

use App\Application\Factory\DTOFactory;
use App\Application\Validator\CreateBasketRequestValidator;
use App\Application\Validator\UpdateBasketRequestValidator;
use App\Domain\BasketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FruitBasketController extends AbstractController
{
    public function __construct(
        protected BasketService $basketService,
        protected DTOFactory $DTOFactory,
    ) {
    }

    /** @Route("/baskets", name="basket_create", methods={"POST"}) */
    public function createBasket(
        Request $request,
        CreateBasketRequestValidator $validator
    ): Response {
        try {
            $validator->validate($request);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        try {
            $basket = $this->basketService->createNewBasket(
                $this->DTOFactory->createBasketDTOFromRequest($request)
            );
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new JsonResponse(
            status: Response::HTTP_CREATED,
            headers: [
                'location' => $this->generateUrl('basket_info', ['basketId' => $basket->getId()]),
            ]
        );
    }

    /** @Route("/baskets", name="baskets_list", methods={"GET"}) */
    public function getAllBaskets(): Response
    {
        return new JsonResponse(
            data: $this->basketService->getAllBaskets(),
            status: Response::HTTP_OK
        );
    }

    /** @Route("/baskets/{basketId}", name="basket_info", methods={"GET"}) */
    public function getBasketInfo(int $basketId): Response
    {
        $basket = $this->basketService->getBasketById($basketId);

        if (empty($basket)) {
            return new JsonResponse(
                status: Response::HTTP_NOT_FOUND
            );
        }

        return new JsonResponse(
            data: $basket,
            status: Response::HTTP_OK
        );
    }

    /** @Route("/baskets/{basketId}", name="basket_update", methods={"PATCH"}) */
    public function updateBasketInfo(
        int $basketId,
        Request $request,
        UpdateBasketRequestValidator $validator
    ): Response {
        try {
            $validator->validate($request);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        try {
            $basket = $this->basketService->updateBasketById(
                $basketId,
                $this->DTOFactory->createBasketUpdateDTOFromRequest($request)
            );
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (empty($basket)) {
            return new JsonResponse(
                status: Response::HTTP_NOT_FOUND
            );
        }

        return new JsonResponse(
            status: Response::HTTP_OK
        );
    }

    /** @Route("/baskets/{basketId}", name="basket_remove", methods={"DELETE"}) */
    public function removeBasket(int $basketId): Response
    {
        if (!$this->basketService->deleteBasketById($basketId)) {
            return new JsonResponse(
                status: Response::HTTP_NOT_FOUND
            );
        }

        return new JsonResponse(
            status: Response::HTTP_OK
        );
    }
}
