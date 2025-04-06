<?php

namespace common\interfaces;

interface PasswordInterface
{
    public function setPassword(string $password): void;
    public function generatePasswordResetToken(): string;
    public function removePasswordResetToken(): void;
    public function isPasswordResetTokenValid(string $token): bool;
} 