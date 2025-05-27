<?php

declare(strict_types=1);

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'ref_i18n')]
class I18N
{
    public const int TYPE_ID_EN = 1;
    public const int TYPE_ID_ES = 2;
    public const int TYPE_ID_UK = 3;

    public const string TYPE_VALUE_EN = 'en';
    public const string TYPE_VALUE_ES = 'es';
    public const string TYPE_VALUE_UK = 'uk';

    public const array MAP_VALUE_KEY = [
        self::TYPE_VALUE_EN => self::TYPE_ID_EN,
        self::TYPE_VALUE_ES => self::TYPE_ID_ES,
        self::TYPE_VALUE_UK => self::TYPE_ID_UK,
    ];

    #[ORM\Id]
    #[ORM\Column(type: 'smallint')]
    private int $id;

    #[ORM\Column(type: 'string', length: 2)]
    private string $code;
}
