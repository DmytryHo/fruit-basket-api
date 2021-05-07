<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Storage;

use App\Domain\BasketDTOInterface;
use App\Domain\BasketInterface;
use App\Domain\BasketStorageInterface;
use App\Domain\BasketUpdateDTOInterface;
use App\Infrastructure\Persistence\Doctrine\Entity\Basket;
use App\Infrastructure\Persistence\Doctrine\Entity\BasketItem;
use App\Infrastructure\Persistence\Doctrine\Repository\BasketRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineBasketStorage implements BasketStorageInterface
{
    public function __construct(
        protected BasketRepository $basketRepository,
        protected EntityManagerInterface $entityManager
    ) {
    }

    public function createBasket(BasketDTOInterface $basketDTO): BasketInterface
    {
        $basket = new Basket(
            $basketDTO->getName(),
            $basketDTO->getMaxCapacity()
        );

        $this->entityManager->persist($basket);
        $this->entityManager->flush();

        return $basket;
    }

    public function getAllBaskets(): array
    {
        return $this->basketRepository->findAll();
    }

    public function getBasketById(int $basketId): ?BasketInterface
    {
        $basket = $this->basketRepository->find($basketId);
        if ($basket instanceof BasketInterface) {
            return $basket;
        }

        return null;
    }

    public function updateBasketById(int $basketId, BasketUpdateDTOInterface $basketDTO): ?BasketInterface
    {
        $basket = $this->basketRepository->find($basketId);
        if ($basket instanceof Basket) {
            if ($basketDTO->getName() !== null) {
                $basket->setName($basketDTO->getName());
                $this->entityManager->persist($basket);
            }
            if ($basketDTO->getMaxCapacity() !== null) {
                $basket->setMaxCapacity($basketDTO->getMaxCapacity());
                $this->entityManager->persist($basket);
            }
        }

        $this->entityManager->flush();

        return $basket;
    }

    public function deleteBasketById(int $basketId): bool
    {
        $basket = $this->basketRepository->find($basketId);

        if (empty($basket)) {
            return false;
        }
        $this->entityManager->remove($basket);
        $this->entityManager->flush();

        return true;
    }

    public function cleanBasketById(int $basketId): bool
    {
        $basket = $this->basketRepository->find($basketId);

        if (empty($basket)) {
            return false;
        }

        if (!empty($basket->getItems())) {
            foreach ($basket->getItems() as $item) {
                $this->entityManager->remove($item);
            }
            $this->entityManager->flush();
        }

        return true;
    }

    /** {@inheritDoc} */
    public function addItemsToBasketById(int $basketId, array $items): bool
    {
        /** @var Basket $basket */
        $basket = $this->basketRepository->find($basketId);

        if (empty($basket)) {
            return false;
        }

        $newItems = [];
        foreach ($items as $itemDto) {
            $newItem = new BasketItem(
                $itemDto->getType(),
                $itemDto->getWeight(),
                $basket
            );
            $newItems[] = $newItem;
        }

        foreach ($newItems as $item) {
            $this->entityManager->persist($item);
        }
        $this->entityManager->flush();

        return true;
    }
}
