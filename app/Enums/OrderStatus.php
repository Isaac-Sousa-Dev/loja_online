<?php

declare(strict_types=1);

namespace App\Enums;

use App\Models\Store;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case SEPARATING = 'separating';
    case DELIVERED = 'delivered';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pendente',
            self::CONFIRMED => 'Confirmado',
            self::SEPARATING => 'Em separação',
            self::DELIVERED => 'Entregue',
            self::COMPLETED => 'Concluído',
            self::CANCELLED => 'Cancelado',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::PENDING => 'bg-amber-100 text-amber-900 border-amber-200',
            self::CONFIRMED => 'bg-sky-100 text-sky-900 border-sky-200',
            self::SEPARATING => 'bg-violet-100 text-violet-900 border-violet-200',
            self::DELIVERED => 'bg-indigo-100 text-indigo-900 border-indigo-200',
            self::COMPLETED => 'bg-emerald-100 text-emerald-900 border-emerald-200',
            self::CANCELLED => 'bg-red-100 text-red-800 border-red-200',
        };
    }

    public function dotClass(): string
    {
        return match ($this) {
            self::PENDING => 'bg-amber-500',
            self::CONFIRMED => 'bg-sky-500',
            self::SEPARATING => 'bg-violet-500',
            self::DELIVERED => 'bg-indigo-500',
            self::COMPLETED => 'bg-emerald-500',
            self::CANCELLED => 'bg-red-500',
        };
    }

    public function isTerminal(): bool
    {
        return $this === self::COMPLETED || $this === self::CANCELLED;
    }

    /**
     * Próximo status no fluxo operacional (respeita módulo de separação na loja).
     */
    public function nextOperational(Store $store): ?self
    {
        return match ($this) {
            self::PENDING => self::CONFIRMED,
            self::CONFIRMED => $store->hasFeature('separation_team')
                ? self::SEPARATING
                : self::DELIVERED,
            self::SEPARATING => self::DELIVERED,
            self::DELIVERED => self::COMPLETED,
            default => null,
        };
    }

    public function canCancel(): bool
    {
        return match ($this) {
            self::PENDING, self::CONFIRMED, self::SEPARATING => true,
            self::DELIVERED, self::COMPLETED, self::CANCELLED => false,
        };
    }

    public function canConfirmFromPending(): bool
    {
        return $this === self::PENDING;
    }
}
