<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repositories;

use App\DBEntityManager;
use App\Entities\Country;
use App\Entities\I18N;
use App\Entities\OS;
use App\Entities\PaymentMethod;
use App\Entities\ProductType;
use App\Repositories\PaymentMethodRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Exception;
use PHPUnit\Framework\TestCase;

class PaymentMethodRepositoryTest extends TestCase
{
    private PaymentMethodRepository $repository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        DBEntityManager::setInstance(null);

        /** @var EntityManager $em */
        $em = DBEntityManager::getInstance();

        $em->getConnection()->executeStatement('TRUNCATE payment_methods_list CASCADE');
        $em->getConnection()->executeStatement('TRUNCATE payment_methods CASCADE');
        $em->getConnection()->executeStatement('TRUNCATE payment_methods__i18n CASCADE');
        $em->getConnection()->executeStatement('TRUNCATE payment_methods__countries CASCADE');
        $em->getConnection()->executeStatement('TRUNCATE payment_systems CASCADE');
        $em->getConnection()->executeStatement('TRUNCATE payment_systems__i18n CASCADE');
        $em->getConnection()->executeStatement('TRUNCATE payment_systems__countries CASCADE');

        $em->getConnection()->executeStatement(<<<SQL
INSERT INTO "payment_systems" ("id", "name", "image_url", "status")
VALUES (1, 'Payment Systems Test', 'https://example.com/image.png', true);

INSERT INTO "payment_systems__countries" ("payment_system_id", "country_id", "image_url")
VALUES (1, 1, 'https://example.com/image_uk.png');

INSERT INTO "payment_methods" ("id", "name", "commission", "pay_url", "status", "priority", "payment_system_id")
VALUES (1, 'Visa', 0.5, 'https://example.com/pay', true, 10, 1);

INSERT INTO "payment_methods__i18n" ("payment_method_id", "i18n_id", "name", "status", "priority")
VALUES (1, 3, 'Віза', true, 5);

INSERT INTO "payment_methods_list" ("product_type_id", "os_id", "amount_min", "amount_max", "payment_method_id")
VALUES (1, 1, 0, 100, 1);

INSERT INTO "payment_methods__countries" ("payment_method_id", "country_id", "status", "priority")
VALUES (1, 1, true, 8);
SQL
        );

        /** @var PaymentMethodRepository $paymentMethodRepository */
        $paymentMethodRepository = $em->getRepository(PaymentMethod::class);

        $this->repository = $paymentMethodRepository;
    }

    /**
     * @throws Exception
     */
    public function testFindAvailableReturnsExpectedData(): void
    {
        $results = $this->repository->findAvailable(
            productTypeId: ProductType::TYPE_ID_BOOK,
            amount: 50.00,
            langId: I18N::TYPE_ID_EN,
            countryId: Country::TYPE_ID_US,
            osId: OS::TYPE_ID_ANDROID
        );

        $this->assertIsArray($results);
        $this->assertNotEmpty($results);

        $row = $results[0];

        $this->assertSame('Visa', $row['name']);
        $this->assertSame(0.5, (float) $row['commission']);
        $this->assertSame('https://example.com/image.png', $row['image_url']);
        $this->assertSame('https://example.com/pay', $row['pay_url']);
    }

    /**
     * @throws Exception
     */
    public function testFindAvailableReturnsExpectedUKData(): void
    {
        $results = $this->repository->findAvailable(
            productTypeId: ProductType::TYPE_ID_BOOK,
            amount: 50.00,
            langId: I18N::TYPE_ID_UK,
            countryId: Country::TYPE_ID_UA,
            osId: OS::TYPE_ID_ANDROID
        );

        $this->assertIsArray($results);
        $this->assertNotEmpty($results);

        $row = $results[0];

        $this->assertSame('Віза', $row['name']);
        $this->assertSame(0.5, (float) $row['commission']);
        $this->assertSame('https://example.com/image_uk.png', $row['image_url']);
        $this->assertSame('https://example.com/pay', $row['pay_url']);
    }

    /**
     * @throws Exception
     */
    public function testFindAvailableReturnsEmptyOnNoMatch(): void
    {
        $results = $this->repository->findAvailable(
            productTypeId: 999,
            amount: 999,
            langId: 999,
            countryId: 999,
            osId: 999
        );

        $this->assertIsArray($results);
        $this->assertEmpty($results);
    }
}
