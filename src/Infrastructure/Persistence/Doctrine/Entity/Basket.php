<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Entity;

use App\Domain\BasketInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Table(name="basket")
 * @ORM\Entity(repositoryClass="App\Infrastructure\Persistence\Doctrine\Repository\BasketRepository")
 */
class Basket implements \JsonSerializable, BasketInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /** @ORM\Column(type="string") */
    private string $name;

    /** @ORM\Column(type="decimal", precision=6, scale=3) */
    private float $maxCapacity;

    /**
     * @ORM\OneToMany(targetEntity="BasketItem", mappedBy="basket")
     */
    private PersistentCollection $items;

    public function __construct(
        string $name,
        float $maxCapacity
    ) {
        $this->name = $name;
        $this->maxCapacity = $maxCapacity;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setMaxCapacity(float $maxCapacity): void
    {
        $this->maxCapacity = $maxCapacity;
    }

    /** @return BasketItem[] */
    public function getItems(): array
    {
        return $this->items->getValues();
    }

    public function getFreeCapacity(): float
    {
        if (!isset($this->items)) {
            return 0;
        }
        $freeCapacity = $this->maxCapacity;
        /** @var BasketItem $item */
        foreach ($this->items->getValues() as $item) {
            $freeCapacity -= $item->getWeight();
        }

        return $freeCapacity;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'items' => $this->items->getValues(),
        ];
    }
}
