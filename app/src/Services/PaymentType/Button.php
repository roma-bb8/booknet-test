<?php

declare(strict_types=1);

namespace App\Services\PaymentType;

readonly class Button
{
    public function __construct(
        private string $name,
        private string $commission,
        private string $imageUrl,
        private string $payUrl,
    )
    {
        // ########################################
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCommission(): string
    {
        return $this->commission;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function getPayUrl(): string
    {
        return $this->payUrl;
    }
}
