<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="basket_item")
 * @ORM\Entity(repositoryClass="App\Infrastructure\Persistence\Doctrine\Repository\BasketItemRepository")
 */
class BasketItem implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /** @ORM\Column(type="BasketItemTypeEnum") */
    private string $type;

    /** @ORM\Column(type="decimal", precision=5, scale=3) */
    private float $weight;

    /**
     * @ORM\ManyToOne(targetEntity="Basket", inversedBy="items")
     * @ORM\JoinColumn(name="basket_id", referencedColumnName="id")
     */
    private Basket $basket;

    public function __construct(
        string $type,
        float $weight,
        Basket $basket
    ) {
        $this->type = $type;
        $this->weight = $weight;
        $this->basket = $basket;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type,
            'weight' => $this->weight,
        ];
    }
}
