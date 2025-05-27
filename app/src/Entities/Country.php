<?php

declare(strict_types=1);

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'ref_countries')]
class Country
{
    public const int TYPE_ID_UA = 1;
    public const int TYPE_ID_KZ = 2;
    public const int TYPE_ID_PL = 3;
    public const int TYPE_ID_US = 4;
    public const int TYPE_ID_DE = 5;
    public const int TYPE_ID_FR = 6;
    public const int TYPE_ID_IT = 7;
    public const int TYPE_ID_IN = 8;

    public const string TYPE_VALUE_UA = 'UA';
    public const string TYPE_VALUE_KZ = 'KZ';
    public const string TYPE_VALUE_PL = 'PL';
    public const string TYPE_VALUE_US = 'US';
    public const string TYPE_VALUE_DE = 'DE';
    public const string TYPE_VALUE_FR = 'FR';
    public const string TYPE_VALUE_IT = 'IT';
    public const string TYPE_VALUE_IN = 'IN';

    public const array MAP_VALUE_KEY = [
        self::TYPE_VALUE_UA => self::TYPE_ID_UA,
        self::TYPE_VALUE_KZ => self::TYPE_ID_KZ,
        self::TYPE_VALUE_PL => self::TYPE_ID_PL,
        self::TYPE_VALUE_US => self::TYPE_ID_US,
        self::TYPE_VALUE_DE => self::TYPE_ID_DE,
        self::TYPE_VALUE_FR => self::TYPE_ID_FR,
        self::TYPE_VALUE_IT => self::TYPE_ID_IT,
        self::TYPE_VALUE_IN => self::TYPE_ID_IN,
    ];

    #[ORM\Id]
    #[ORM\Column(type: 'smallint')]
    private int $id;

    #[ORM\Column(type: 'string', length: 2)]
    private string $code;
}
