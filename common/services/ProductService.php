<?php

namespace common\services;

use common\interfaces\ProductInterface;
use Yii;

class ProductService implements ProductInterface
{
    private static array $products = [
        0 => 'Выберите продукт',
        1 => 'яблоки',
        2 => 'апельсины',
        3 => 'мандарины'
    ];

    public function getProductName(int $productId): string
    {
        if (array_key_exists($productId, self::$products)) {
            return self::$products[$productId];
        }
        return Yii::t('bid', "Товар не найден");
    }

    public function getAvailableProducts(): array
    {
        return self::$products;
    }
} 