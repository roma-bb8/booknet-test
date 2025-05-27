<?php

declare(strict_types=1);

namespace App\Entities;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'payment_systems')]
class PaymentSystem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 55, unique: true)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $description;

    #[ORM\Column(type: 'string', length: 255)]
    private string $imageUrl;

    #[ORM\Column(type: 'boolean')]
    private bool $status = true;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $createdAt;
}
