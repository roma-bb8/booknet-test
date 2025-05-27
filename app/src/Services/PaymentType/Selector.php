<?php

declare(strict_types=1);

namespace App\Services\PaymentType;

use App\Entities\PaymentMethod;
use App\DBEntityManager;
use App\Repositories\PaymentMethodRepository;
use Doctrine\DBAL\Exception;

class Selector
{
    private bool $yooMoneyDirectPayments = false;

    public function __construct(
        private readonly int   $productTypeId,
        private readonly float $amount,
        private readonly int   $langId,
        private readonly int   $countryId,
        private readonly int   $osId,
    )
    {
        // ########################################
    }

    public function enableYooMoneyDirectPayments(): void
    {
        $this->yooMoneyDirectPayments = true;
    }

    /**
     * @return Button[]
     * @throws Exception
     */
    public function getButtons(): array
    {
        /** @var PaymentMethodRepository $paymentMethodRepository */
        $paymentMethodRepository = DBEntityManager::getInstance()->getRepository(PaymentMethod::class);

        if ($this->yooMoneyDirectPayments) {
            $data = $paymentMethodRepository->findAvailableByFilter($this->productTypeId,
                $this->amount,
                $this->langId,
                $this->countryId,
                $this->osId,
                ['Yoomoney'],
                ['Connectum' => 'Bank Card', 'CardPay' => 'Bank Card']
            );
        } else {
            $data = $paymentMethodRepository->findAvailable(
                $this->productTypeId,
                $this->amount,
                $this->langId,
                $this->countryId,
                $this->osId,
            );
        }

        $list = [];
        foreach ($data as $item) {
            $dto = ButtonFactory::create([
                'name' => $item['name'],
                'commission' => $item['commission'],
                'image_url' => $item['image_url'],
                'pay_url' => $item['pay_url']
            ]);

            $list = array_merge($list, $dto);
        }

        return $list;
    }
}
