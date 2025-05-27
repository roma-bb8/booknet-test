<?php

declare(strict_types=1);

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'payment_methods__countries')]
class PaymentMethodCountry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: PaymentMethod::class)]
    #[ORM\JoinColumn(name: 'payment_method_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private PaymentMethod $paymentMethod;

    #[ORM\ManyToOne(targetEntity: Country::class)]
    #[ORM\JoinColumn(name: 'country_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Country $country;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $status;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $priority;
}
