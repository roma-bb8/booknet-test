<?php

declare(strict_types=1);

namespace App\Entities;

use App\Repositories\PaymentMethodRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentMethodRepository::class)]
#[ORM\Table(name: 'payment_methods')]
class PaymentMethod
{
    public const string SEPARATOR = ';';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: PaymentSystem::class)]
    #[ORM\JoinColumn(name: 'payment_system_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private PaymentSystem $paymentSystem;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, options: ['default' => 0.00])]
    private string $commission = '0.00';

    #[ORM\Column(name: 'pay_url', type: 'string', length: 255, nullable: true)]
    private ?string $payUrl;

    #[ORM\Column(type: 'boolean')]
    private bool $status = true;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $priority;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: true)]
    private ?DateTimeInterface $updatedAt;

    #[ORM\Column(name: 'created_at', type: 'datetime')]
    private DateTimeInterface $createdAt;
}
