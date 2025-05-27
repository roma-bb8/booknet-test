<?php

declare(strict_types=1);

namespace App\Entities;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'payment_methods_list')]
class PaymentMethodList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: PaymentMethod::class)]
    #[ORM\JoinColumn(name: 'payment_method_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private PaymentMethod $paymentMethod;

    #[ORM\ManyToOne(targetEntity: ProductType::class)]
    #[ORM\JoinColumn(name: 'product_type_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ProductType $productType;

    #[ORM\ManyToOne(targetEntity: Country::class)]
    #[ORM\JoinColumn(name: 'country_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Country $country;

    #[ORM\Column(name: 'amount_min', type: 'decimal', precision: 7, scale: 2, options: ['default' => 0.00])]
    private string $amountMin = '0.00';

    #[ORM\Column(name: 'amount_max', type: 'decimal', precision: 7, scale: 2, options: ['default' => 10000.00])]
    private string $amountMax = '10000.00';

    #[ORM\ManyToOne(targetEntity: OS::class)]
    #[ORM\JoinColumn(name: 'os_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private OS $os;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: true)]
    private ?DateTimeInterface $updatedAt;

    #[ORM\Column(name: 'created_at', type: 'datetime')]
    private DateTimeInterface $createdAt;
}
