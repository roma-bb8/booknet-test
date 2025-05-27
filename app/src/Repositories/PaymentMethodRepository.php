<?php

declare(strict_types=1);

namespace App\Repositories;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityRepository;

class PaymentMethodRepository extends EntityRepository
{
    /**
     * @return list<array{name:string, commission:float, image_url:string, pay_url:string}>
     * @throws Exception
     */
    public function findAvailable(int $productTypeId, float $amount, int $langId, int $countryId, int $osId): array
    {
        $sql = <<<SQL
SELECT COALESCE(pmi18n.name, pm.name)        AS name,
       pm.commission                         AS commission,
       COALESCE(psc.image_url, ps.image_url) AS image_url,
       pm.pay_url                            AS pay_url
FROM payment_methods_list pml
         LEFT JOIN payment_methods pm ON pm.id = pml.payment_method_id
         LEFT JOIN payment_methods__i18n pmi18n ON pm.id = pmi18n.payment_method_id AND pmi18n.i18n_id = :langId
         LEFT JOIN payment_methods__countries pmc ON pm.id = pmc.payment_method_id AND pmc.country_id = :countryId
         LEFT JOIN payment_systems ps ON ps.id = pm.payment_system_id
         LEFT JOIN payment_systems__i18n psi18n ON ps.id = psi18n.payment_system_id AND psi18n.i18n_id = :langId
         LEFT JOIN payment_systems__countries psc ON ps.id = psc.payment_system_id AND psc.country_id = :countryId
WHERE pml.product_type_id = :productTypeId
  AND pml.os_id = :osId
  AND :amount BETWEEN pml.amount_min AND pml.amount_max
  AND LEAST(
              COALESCE(pmc.status::int, 1),
              COALESCE(pmi18n.status::int, 1),
              COALESCE(pm.status::int, 1),
              COALESCE(psi18n.status::int, 1),
              COALESCE(ps.status::int, 1)
      )::boolean = true
ORDER BY GREATEST(COALESCE(pmc.priority, 0), COALESCE(pmi18n.priority, 0), COALESCE(pm.priority, 0))
SQL;

        return $this->getEntityManager()->getConnection()->executeQuery($sql, [
            'langId' => $langId,
            'countryId' => $countryId,
            'productTypeId' => $productTypeId,
            'osId' => $osId,
            'amount' => $amount
        ])->fetchAllAssociative();
    }

    /**
     * @return list<array{name:string, commission:float, image_url:string, pay_url:string}>
     */
    public function findAvailableByFilter(
        int   $productTypeId,
        float $amount,
        int   $langId,
        int   $countryId,
        int   $osId,
        array $included = [],
        array $excluded = [],
    ): array
    {
        // Если клиент передал параметр
        // (в аргумент или с помощью метода сеттера типа $paymentTypeSelector->enableYoomoneyDirectPayments()),
        // что для данного товара доступен метод оплаты через систему Yoomoney,
        // то исключить способы оплаты картой через Connectum и CardPay,
        // если они были доступны.

        // TODO: Не дуже зрозуміло які умови ...
        // TODO: 1 мені потрібно проігнорувати всі перевірки і включити Yoomoney
        // TODO: 2 чи просто якщо є Yoomoney то вивести і вимкнути Connectum и CardPay
        // TODO: 3 а якщо Yoomoney немає то Connectum и CardPay виводити ?

        // TODO: В цілому я б зробив окремий SQL з урахуванням included та excluded payment information

        return [];
    }
}
