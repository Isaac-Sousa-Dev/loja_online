<?php

namespace App\DTO;

class ProductDTO
{
    public function __construct(
        public string $name,
        public float $price,
        public ?string $description = null,
        public ?float $pricePromotional,
        public float $cost,
        public ?string $imageMain = null
    ) {}

    public static function fromRequest(array $data, ?string $imageMain = null): self
    {
        return new self(
            name: $data['name'],
            price: self::formatPrice($data['price']),
            description: $data['description'],
            pricePromotional: isset($data['price_promotional']) ? self::formatPrice($data['price_promotional']) : null,
            cost: self::formatPrice($data['cost']),
            imageMain: $imageMain
        );
    }

    private static function formatPrice(string $price): float
    {
        $price = explode(',', $price)[0];
        $price = str_replace('.', '', $price);
        return (float) $price;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,    
            'price_promotional' => $this->pricePromotional,
            'cost' => $this->cost,
            'image_main' => $this->imageMain,
        ];
    }
}