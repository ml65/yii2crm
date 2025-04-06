<?php

namespace common\interfaces;

interface StatusInterface
{
    public function getStatusTitle(int $status): string;
    public function getAvailableStatuses(bool $all = false): array;
} 