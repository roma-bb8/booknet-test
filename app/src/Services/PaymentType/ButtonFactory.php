<?php

declare(strict_types=1);

namespace App\Services\PaymentType;

use App\Entities\PaymentMethod;

class ButtonFactory
{
    protected static function oneButton(array $data): array
    {
        $dto = new Button(
            $data['name'],
            $data['commission'],
            $data['image_url'],
            $data['pay_url'],
        );

        return [$dto];
    }

    protected static function manyButton(array $data): array
    {
        $imageUrls = explode(PaymentMethod::SEPARATOR, $data['image_url']);
        $names = explode(PaymentMethod::SEPARATOR, $data['name']);

        $list = [];
        foreach ($names as $index => $name) {
            $list[] = new Button(
                $name,
                $data['commission'],
                $imageUrls[$index] ?? $imageUrls[0],
                $data['pay_url'],
            );
        }

        return $list;
    }

    /**
     * @return Button[]
     */
    public static function create(array $data): array
    {
        if (strpos($data['name'], PaymentMethod::SEPARATOR)) {
            return self::manyButton($data);
        }

        return self::oneButton($data);
    }
}
