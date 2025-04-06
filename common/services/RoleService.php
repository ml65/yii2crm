<?php

namespace common\services;

use common\interfaces\RoleInterface;
use Yii;

class RoleService implements RoleInterface
{
    private int $role;

    public const ROLE_GUEST = 0;
    public const ROLE_ADMIN = 1024;
    public const ROLE_MANAGER = 512;
    public const ROLE_USER = 1;

    protected static $rolesTitles = [
        self::ROLE_GUEST    => 'Гость',
        self::ROLE_USER     => 'Пользователь',
        self::ROLE_MANAGER  => 'Менеджер',
        self::ROLE_ADMIN    => 'Админ'
    ];

    public function __construct(int $role)
    {
        $this->role = $role;
    }

    public function getAvailableRoles(bool $all = false): array
    {
        if ($all) {
            return [
                null => Yii::t('user', "Все"),
                self::ROLE_GUEST    => Yii::t('user', static::$rolesTitles[self::ROLE_GUEST]),
                self::ROLE_USER     => Yii::t('user', static::$rolesTitles[self::ROLE_USER]),
                self::ROLE_MANAGER  => Yii::t('user', static::$rolesTitles[self::ROLE_MANAGER]),
                self::ROLE_ADMIN    => Yii::t('user', static::$rolesTitles[self::ROLE_ADMIN]),
            ];
        }

        return [
            self::ROLE_GUEST    => Yii::t('user', static::$rolesTitles[self::ROLE_GUEST]),
            self::ROLE_USER     => Yii::t('user', static::$rolesTitles[self::ROLE_USER]),
            self::ROLE_MANAGER  => Yii::t('user', static::$rolesTitles[self::ROLE_MANAGER]),
            self::ROLE_ADMIN    => Yii::t('user', static::$rolesTitles[self::ROLE_ADMIN]),
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isManager(): bool
    {
        return $this->role === self::ROLE_MANAGER;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }
} 