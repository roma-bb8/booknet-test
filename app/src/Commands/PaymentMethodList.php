<?php

declare(strict_types=1);

namespace App\Commands;

use App\Entities\Country;
use App\Entities\I18N;
use App\Entities\OS;
use App\Entities\ProductType;
use App\Services\PaymentType\Selector;
use Doctrine\DBAL\Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'payment-method:list',
    description: 'Payment Method List'
)]
class PaymentMethodList extends Command
{
    protected function configure(): void
    {
        $this
            ->addOption(
                'productType',
                null,
                InputOption::VALUE_REQUIRED,
                implode(', ', array_keys(ProductType::MAP_VALUE_KEY)),
                ProductType::TYPE_VALUE_BOOK
            )
            ->addOption(
                'amount',
                null,
                InputOption::VALUE_REQUIRED,
                'The amount must be greater than 0',
                '85.10'
            )
            ->addOption(
                'lang',
                null,
                InputOption::VALUE_REQUIRED,
                implode(', ', array_keys(I18N::MAP_VALUE_KEY)),
                I18N::TYPE_VALUE_EN
            )
            ->addOption(
                'country',
                null,
                InputOption::VALUE_REQUIRED,
                implode(', ', array_keys(Country::MAP_VALUE_KEY)),
                Country::TYPE_VALUE_IN
            )
            ->addOption(
                'os',
                null,
                InputOption::VALUE_REQUIRED,
                implode(', ', array_keys(OS::MAP_VALUE_KEY)),
                OS::TYPE_VALUE_ANDROID
            )
            ->addOption(
                'yoomoney',
                null,
                InputOption::VALUE_NONE,
                'Yoomoney ...',
            );
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $productType = $input->getOption('productType');
        if (!in_array($productType, array_keys(ProductType::MAP_VALUE_KEY), true)) {
            $io->error(sprintf(
                'Incorrect Product Type. Allowed: %s',
                implode(', ', array_keys(ProductType::MAP_VALUE_KEY))),
            );

            return Command::FAILURE;
        }
        $productTypeId = ProductType::MAP_VALUE_KEY[$productType];

        $amount = (float) $input->getOption('amount');
        if ($amount <= 0) {
            $io->error('The amount must be greater than 0.');

            return Command::FAILURE;
        }

        $lang = strtolower($input->getOption('lang'));
        if (!in_array($lang, array_keys(I18N::MAP_VALUE_KEY), true)) {
            $io->error(sprintf(
                'Incorrect language. Allowed: %s',
                implode(', ', array_keys(I18N::MAP_VALUE_KEY)),
            ));

            return Command::FAILURE;
        }
        $langId = I18N::MAP_VALUE_KEY[$lang];

        $countryCode = strtoupper($input->getOption('country'));
        if (!in_array($countryCode, array_keys(Country::MAP_VALUE_KEY), true)) {
            $io->error(sprintf(
                'The country code must follow the ISO-3166 format. Allowed: %s',
                implode(', ', array_keys(Country::MAP_VALUE_KEY)),
            ));

            return Command::FAILURE;
        }
        $countryId = Country::MAP_VALUE_KEY[$countryCode];

        $userOs = strtolower($input->getOption('os'));
        if (!in_array($userOs, array_keys(OS::MAP_VALUE_KEY), true)) {
            $io->error(sprintf(
                'Incorrect OS. Allowed: %s',
                implode(', ', array_keys(OS::MAP_VALUE_KEY)),
            ));

            return Command::FAILURE;
        }
        $osId = OS::MAP_VALUE_KEY[$userOs];

        $headers = ['name', 'commission', 'imageUrl', 'payUrl'];
        $rows = [];

        $paymentTypeSelector = new Selector($productTypeId, $amount, $langId, $countryId, $osId);
        if (true === (bool) $input->getOption('yoomoney')) {
            $paymentTypeSelector->enableYooMoneyDirectPayments();
        }

        foreach ($paymentTypeSelector->getButtons() as $btn) {
            $rows[] = [$btn->getName(), $btn->getCommission(), $btn->getImageUrl(), $btn->getPayUrl()];
        }

        $io->table($headers, $rows);

        return Command::SUCCESS;
    }
}
