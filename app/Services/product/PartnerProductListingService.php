<?php

declare(strict_types=1);

namespace App\Services\product;

use Illuminate\Database\Eloquent\Builder;

class PartnerProductListingService
{
    /**
     * @param  Builder<\App\Models\Product>  $query
     * @param  array{name?: string, category_id?: int, brand_id?: int, gender?: string, status: string}  $filters
     */
    public function applyFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['name'])) {
            $term = $filters['name'];
            $query->where('name', 'like', '%'.addcslashes($term, '%_\\').'%');
        }

        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        if (! empty($filters['gender'])) {
            $columnGender = match ($filters['gender']) {
                'masculine' => 'M',
                'feminine' => 'F',
                default => $filters['gender'],
            };
            $query->where('gender', $columnGender);
        }

        if ($filters['status'] === 'active') {
            $query->where('is_active', true);
        } elseif ($filters['status'] === 'inactive') {
            $query->where('is_active', false);
        }
    }
}
