<?php

declare(strict_types=1);

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'payment_systems__i18n')]
class PaymentSystemI18N
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: PaymentSystem::class)]
    #[ORM\JoinColumn(name: 'payment_system_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private PaymentSystem $paymentSystem;

    #[ORM\ManyToOne(targetEntity: I18N::class)]
    #[ORM\JoinColumn(name: 'i18n_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private I18N $i18n;

    #[ORM\Column(type: 'string', length: 55, nullable: true)]
    private ?string $name;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $status;
}
