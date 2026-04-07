<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'name',
        'type',
        'partner_id',
        'logo_brand',
    ];

    protected static function booted(): void
    {
        static::creating(function (Brand $brand): void {
            $raw = $brand->codigo;
            if ($raw !== null && $raw !== '') {
                $brand->codigo = (string) $raw;

                return;
            }

            $brand->codigo = self::generateUniqueCodigo((string) ($brand->name ?? ''));
        });
    }

    private static function generateUniqueCodigo(string $name): string
    {
        $ascii = Str::ascii($name);
        $base = Str::upper(Str::slug($ascii, ''));
        $base = preg_replace('/[^A-Z0-9]/', '', $base) ?? '';
        if ($base === '' || strlen($base) < 2) {
            $base = 'MARCA';
        }
        $base = substr($base, 0, 20);

        $codigo = $base;
        $n = 0;
        while (self::query()->where('codigo', $codigo)->exists()) {
            $n++;
            $codigo = substr($base, 0, 14) . str_pad((string) $n, 6, '0', STR_PAD_LEFT);
        }

        return $codigo;
    }
}
