<?php

declare(strict_types=1);

namespace App\Tests\Integration\Commands;

use App\Commands\PaymentMethodList;
use App\DBEntityManager;
use App\Entities\Country;
use App\Entities\I18N;
use App\Entities\OS;
use App\Entities\ProductType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class PaymentMethodListTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        DBEntityManager::setInstance(null);
    }

    public function testPaymentMethodList(): void
    {
        $app = new Application('Test');

        $app->add(new PaymentMethodList());

        $cmd = $app->find('payment-method:list');

        $cmdTester = new CommandTester($cmd);

        $cmdTester->execute([
            '--productType' => ProductType::TYPE_VALUE_BOOK,
            '--amount' => 3,
            '--lang' => I18N::TYPE_VALUE_EN,
            '--country' => Country::TYPE_VALUE_UA,
            '--os' => OS::TYPE_VALUE_ANDROID,
        ]);

        $cmdTester->assertCommandIsSuccessful();
    }
}
