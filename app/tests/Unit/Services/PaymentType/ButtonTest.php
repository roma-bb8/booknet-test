<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\PaymentType;

use App\Services\PaymentType\Button;
use PHPUnit\Framework\TestCase;

class ButtonTest extends TestCase
{
    public function testButtonGettersReturnCorrectValues(): void
    {
        $button = new Button(
            name: 'Google Pay',
            commission: '0.00',
            imageUrl: 'https://example.com/gpay.png',
            payUrl: 'https://pay.example.com/gpay'
        );

        $this->assertSame('Google Pay', $button->getName());
        $this->assertSame('0.00', $button->getCommission());
        $this->assertSame('https://example.com/gpay.png', $button->getImageUrl());
        $this->assertSame('https://pay.example.com/gpay', $button->getPayUrl());
    }
}
