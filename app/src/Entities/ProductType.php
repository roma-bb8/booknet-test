<?php

declare(strict_types=1);

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'ref_product_type')]
class ProductType
{
    public const int TYPE_ID_BOOK = 1;
    public const int TYPE_ID_REWARD = 2;
    public const int TYPE_ID_WALLET_REFILL = 3;

    public const string TYPE_VALUE_BOOK = 'book';
    public const string TYPE_VALUE_REWARD = 'reward';
    public const string TYPE_VALUE_WALLET_REFILL = 'walletRefill';

    public const array MAP_VALUE_KEY = [
        self::TYPE_VALUE_WALLET_REFILL => self::TYPE_ID_WALLET_REFILL,
        self::TYPE_VALUE_REWARD => self::TYPE_ID_REWARD,
        self::TYPE_VALUE_BOOK => self::TYPE_ID_BOOK,
    ];

    #[ORM\Id]
    #[ORM\Column(type: 'smallint')]
    private int $id;

    #[ORM\Column(type: 'string', length: 12)]
    private string $name;
}
