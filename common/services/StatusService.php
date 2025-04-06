<?php

namespace common\services;

use common\interfaces\StatusInterface;
use Yii;

class StatusService implements StatusInterface
{
    public const STATUS_NEW = 0;
    public const STATUS_ACCEPTED = 1;
    public const STATUS_REJECTED = 2;
    public const STATUS_DEFECT = 4;

    protected static array $statusTitles = [
        self::STATUS_NEW        => 'Новая',
        self::STATUS_ACCEPTED   => 'Принята',
        self::STATUS_REJECTED   => 'Отказана',
        self::STATUS_DEFECT     => 'Брак'
    ];

    public function getStatusTitle(int $status): string
    {
        if (array_key_exists($status, self::$statusTitles)) {
            return self::$statusTitles[$status];
        }
        return '';
    }

    public function getAvailableStatuses(bool $all = false): array
    {
        if ($all) {
            return [
                null => Yii::t('user', "Все"),
                self::STATUS_NEW        => Yii::t('user', self::$statusTitles[self::STATUS_NEW]),
                self::STATUS_ACCEPTED   => Yii::t('user', self::$statusTitles[self::STATUS_ACCEPTED]),
                self::STATUS_REJECTED   => Yii::t('user', self::$statusTitles[self::STATUS_REJECTED]),
                self::STATUS_DEFECT     => Yii::t('user', self::$statusTitles[self::STATUS_DEFECT]),
            ];
        }

        return [
            self::STATUS_NEW        => Yii::t('user', self::$statusTitles[self::STATUS_NEW]),
            self::STATUS_ACCEPTED   => Yii::t('user', self::$statusTitles[self::STATUS_ACCEPTED]),
            self::STATUS_REJECTED   => Yii::t('user', self::$statusTitles[self::STATUS_REJECTED]),
            self::STATUS_DEFECT     => Yii::t('user', self::$statusTitles[self::STATUS_DEFECT]),
        ];
    }
} 