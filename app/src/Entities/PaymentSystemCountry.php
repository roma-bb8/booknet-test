<?php

declare(strict_types=1);

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'payment_systems__countries')]
class PaymentSystemCountry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: PaymentSystem::class)]
    #[ORM\JoinColumn(name: 'payment_system_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private PaymentSystem $paymentSystem;

    #[ORM\ManyToOne(targetEntity: Country::class)]
    #[ORM\JoinColumn(name: 'country_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Country $country;

    #[ORM\Column(name: 'image_url', type: 'string', length: 255, nullable: true)]
    private ?string $imageUrl;
}
