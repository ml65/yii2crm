<?php

namespace common\services;

use common\interfaces\PasswordInterface;
use Yii;

class PasswordService implements PasswordInterface
{
    private string $passwordHash;
    private ?string $passwordResetToken;

    public function __construct(string $passwordHash, ?string $passwordResetToken = null)
    {
        $this->passwordHash = $passwordHash;
        $this->passwordResetToken = $passwordResetToken;
    }

    public function setPassword(string $password): void
    {
        $this->passwordHash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generatePasswordResetToken(): string
    {
        $this->passwordResetToken = Yii::$app->security->generateRandomString() . '_' . time();
        return $this->passwordResetToken;
    }

    public function removePasswordResetToken(): void
    {
        $this->passwordResetToken = null;
    }

    public function isPasswordResetTokenValid(string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }
} 