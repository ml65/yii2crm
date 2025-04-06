<?php

namespace common\interfaces;

interface RoleInterface
{
    public function getAvailableRoles(bool $all = false): array;
    public function isAdmin(): bool;
    public function isManager(): bool;
    public function isUser(): bool;
} 