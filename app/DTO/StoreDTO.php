<?php

namespace App\DTO;

class StoreDTO
{
    public function __construct(
        public int $id,
        public string $storeName,
        public string $storeEmail,
        public int $storePhone,
        public string $storeCpfCnpj,
        public ?string $logo = null,
        public ?string $banner = null,
    ) {}

    public static function fromRequest(array $data, $storeId): self
    {
        return new self(
            id: $storeId,
            storeName: $data['store_name'],
            storeEmail: $data['store_email'],
            storePhone: self::formatPhone($data['store_phone']),
            storeCpfCnpj: $data['store_cpf_cnpj'],
            logo: isset($data['logo']) ? $data['logo'] : null,
            banner: isset($data['banner']) ? $data['banner'] : null,
        );
    }

    private static function formatPhone(string $phone): int
    {
        $phone = str_replace(['(', ')', '-', ' '], '', $phone);
        return (int) $phone;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'store_name' => $this->storeName,
            'store_email' => $this->storeEmail,
            'store_phone' => $this->storePhone,
            'store_cpf_cnpj' => $this->storeCpfCnpj,
            'logo' => $this->logo,
            'banner' => $this->banner,
        ];
    }
}