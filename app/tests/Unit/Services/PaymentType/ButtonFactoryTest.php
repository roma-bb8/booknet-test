<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\PaymentType;

use App\Services\PaymentType\Button;
use App\Services\PaymentType\ButtonFactory;
use PHPUnit\Framework\TestCase;

class ButtonFactoryTest extends TestCase
{
    public function testCreateReturnsSingleButtonWhenNoSeparator(): void
    {
        $data = [
            'name' => 'Visa',
            'commission' => '0.00',
            'image_url' => 'https://example.com/visa.png',
            'pay_url' => 'https://pay.example.com/visa',
        ];

        $buttons = ButtonFactory::create($data);

        $this->assertCount(1, $buttons);
        $this->assertInstanceOf(Button::class, $buttons[0]);
        $this->assertSame('Visa', $buttons[0]->getName());
        $this->assertSame('https://example.com/visa.png', $buttons[0]->getImageUrl());
    }

    public function testCreateReturnsMultipleButtonsWhenSeparatorUsed(): void
    {
        $data = [
            'name' => 'Visa;Mastercard',
            'commission' => '1.00',
            'image_url' => 'https://example.com/visa.png;https://example.com/mastercard.png',
            'pay_url' => 'https://pay.example.com/cards',
        ];

        $buttons = ButtonFactory::create($data);

        $this->assertCount(2, $buttons);

        $this->assertSame('Visa', $buttons[0]->getName());
        $this->assertSame('https://example.com/visa.png', $buttons[0]->getImageUrl());

        $this->assertSame('Mastercard', $buttons[1]->getName());
        $this->assertSame('https://example.com/mastercard.png', $buttons[1]->getImageUrl());
    }

    public function testCreateUsesFirstImageIfSecondMissing(): void
    {
        $data = [
            'name' => 'Visa;Mastercard',
            'commission' => '1.00',
            'image_url' => 'https://example.com/only-one.png',
            'pay_url' => 'https://pay.example.com/cards',
        ];

        $buttons = ButtonFactory::create($data);

        $this->assertCount(2, $buttons);
        $this->assertSame('https://example.com/only-one.png', $buttons[0]->getImageUrl());
        $this->assertSame('https://example.com/only-one.png', $buttons[1]->getImageUrl());
    }
}
