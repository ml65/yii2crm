<?php

namespace common\interfaces;

interface ProductInterface
{
    public function getProductName(int $productId): string;
    public function getAvailableProducts(): array;
} 