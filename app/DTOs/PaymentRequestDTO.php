<?php

namespace App\DTOs;

class PaymentRequestDTO
{
    public function __construct(
        public string $OrderId,
        public string $Amount,
        public string $Pan,
        public string $Expiry,
        public string $Cvv2,
        public string $CardholderName,
        public ?string $Email = null,
        public ?string $Tel = null,
        public ?string $CustomerIp = null,
        public int $InstallmentCount = 0
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'OrderId' => $this->OrderId,
            'Amount' => $this->Amount,
            'Pan' => $this->Pan,
            'Expiry' => $this->Expiry,
            'Cvv2' => $this->Cvv2,
            'CardholderName' => $this->CardholderName,
            'Email' => $this->Email,
            'Tel' => $this->Tel,
            'CustomerIp' => $this->CustomerIp,
            'InstallmentCount' => $this->InstallmentCount
        ], function ($value) {
            return $value !== null;
        });
    }
} 