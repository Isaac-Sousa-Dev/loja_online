<?php

declare(strict_types=1);

namespace App\Enums;

enum WholesaleCountMode: string
{
    case PRODUCT = 'product';
    case CART = 'cart';

    public function label(): string
    {
        return match ($this) {
            self::PRODUCT => 'Por peça (produto)',
            self::CART => 'Por múltiplas peças (carrinho)',
        };
    }
}
