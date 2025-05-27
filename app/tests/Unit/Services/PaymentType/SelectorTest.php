<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\PaymentType;

use App\Entities\Country;
use App\Entities\I18N;
use App\Entities\OS;
use App\Entities\PaymentMethod;
use App\Entities\ProductType;
use App\Services\PaymentType\Button;
use App\Services\PaymentType\Selector;
use App\Repositories\PaymentMethodRepository;
use App\DBEntityManager;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SelectorTest extends TestCase
{
    private MockObject $paymentMethodRepositoryMock;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->paymentMethodRepositoryMock = $this->createMock(PaymentMethodRepository::class);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock
            ->method('getRepository')
            ->with(PaymentMethod::class)
            ->willReturn($this->paymentMethodRepositoryMock);

        DBEntityManager::setInstance($entityManagerMock);
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function testGetButtonsWithoutYoomoney(): void
    {
        $this->paymentMethodRepositoryMock
            ->expects($this->once())
            ->method('findAvailable')
            ->willReturn([
                [
                    'name' => 'Visa',
                    'commission' => '0.00',
                    'image_url' => 'https://example.com/visa.png',
                    'pay_url' => 'https://pay.example.com/visa',
                ]
            ]);

        $selector = new Selector(
            ProductType::TYPE_ID_BOOK,
            100.00,
            I18N::TYPE_ID_EN,
            Country::TYPE_ID_UA,
            OS::TYPE_ID_ANDROID,
        );

        $buttons = $selector->getButtons();

        $this->assertCount(1, $buttons);
        $this->assertInstanceOf(Button::class, $buttons[0]);
        $this->assertSame('Visa', $buttons[0]->getName());
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function testGetButtonsWithYoomoney(): void
    {
        $this->paymentMethodRepositoryMock
            ->expects($this->once())
            ->method('findAvailableByFilter')
            ->with(
                ProductType::TYPE_ID_BOOK,
                100.00,
                I18N::TYPE_ID_EN,
                Country::TYPE_ID_UA,
                OS::TYPE_ID_ANDROID,
                ['Yoomoney'],
                ['Connectum' => 'Bank Card', 'CardPay' => 'Bank Card']
            )
            ->willReturn([
                [
                    'name' => 'Yoomoney',
                    'commission' => '1.00',
                    'image_url' => 'https://example.com/ym.png',
                    'pay_url' => 'https://pay.example.com/ym',
                ]
            ]);

        $selector = new Selector(
            ProductType::TYPE_ID_BOOK,
            100.00,
            I18N::TYPE_ID_EN,
            Country::TYPE_ID_UA,
            OS::TYPE_ID_ANDROID,
        );

        $selector->enableYooMoneyDirectPayments();

        $buttons = $selector->getButtons();

        $this->assertCount(1, $buttons);
        $this->assertInstanceOf(Button::class, $buttons[0]);
        $this->assertSame('Yoomoney', $buttons[0]->getName());
    }
}
