<?php

namespace common\services;

use common\interfaces\AuthInterface;
use Yii;

class AuthService implements AuthInterface
{
    private string $passwordHash;
    private string $authKey;

    public function __construct(string $passwordHash, string $authKey)
    {
        $this->passwordHash = $passwordHash;
        $this->authKey = $authKey;
    }

    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->passwordHash);
    }

    public function generateAuthKey(): string
    {
        return Yii::$app->security->generateRandomString();
    }

    public function validateAuthKey(string $authKey): bool
    {
        return $this->authKey === $authKey;
    }
} 