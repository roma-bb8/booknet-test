<?php

declare(strict_types=1);

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'ref_os')]
class OS
{
    public const int TYPE_ID_ANDROID = 1;
    public const int TYPE_ID_ISO = 2;
    public const int TYPE_ID_WINDOWS = 3;

    public const string TYPE_VALUE_ANDROID = 'android';
    public const string TYPE_VALUE_ISO = 'ios';
    public const string TYPE_VALUE_WINDOWS = 'windows';

    public const array MAP_VALUE_KEY = [
        self::TYPE_VALUE_ANDROID => self::TYPE_ID_ANDROID,
        self::TYPE_VALUE_ISO => self::TYPE_ID_ISO,
        self::TYPE_VALUE_WINDOWS => self::TYPE_ID_WINDOWS,
    ];

    #[ORM\Id]
    #[ORM\Column(type: 'smallint')]
    private int $id;

    #[ORM\Column(type: 'string', length: 7)]
    private string $name;
}
