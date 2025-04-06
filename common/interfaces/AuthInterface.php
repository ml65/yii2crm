<?php

namespace common\interfaces;

interface AuthInterface
{
    public function validatePassword(string $password): bool;
    public function generateAuthKey(): string;
    public function validateAuthKey(string $authKey): bool;
} 