<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bid".
 *
 * @property int $id
 * @property string $username
 * @property string $title
 * @property int $product_id
 * @property string|null $phone
 * @property string|null $comment
 * @property float $price
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class Bid extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_ACCEPTED = 1;
    const STATUS_REJECTED = 2;
    const STATUS_DEFECT = 4;

    protected static $statusTitles = [
        self::STATUS_NEW        => 'Новая',
        self::STATUS_ACCEPTED   => 'Принята',
        self::STATUS_REJECTED   => 'Отказана',
        self::STATUS_DEFECT     => 'Брак'
    ];

    /* эмуляция работы с БД в модели в поле записываем id продукта*/

    public static $productsBD = [
        0 => 'Выберите продукт',
        1 => 'яблоки',
        2 => 'апельсины',
        3 =>'мандарины'
    ];


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bid';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'title', 'product_id', 'price'], 'required'],
            [['comment'], 'string'],
            [['price'], 'number'],
            [['status', 'product_id'], 'integer'],
            [['username', 'title'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 11],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('bid', 'ID'),
            'username' => Yii::t('bid', 'ФИО заказчика'),
            'title' => Yii::t('bid', 'Наименование заявки'),
            'product_id' => Yii::t('bid', 'Продукт'),
            'phone' => Yii::t('bid', 'Телефон'),
            'comment' => Yii::t('bid', 'Комментарий'),
            'price' => Yii::t('bid', 'Цена'),
            'status' => Yii::t('bid', 'Статус'),
            'created_at' => Yii::t('bid', 'Создано'),
            'updated_at' => Yii::t('bid', 'Обновлено'),
        ];
    }

    public function getProductName($product_id = null)
    {
        if (!$product_id) {
            $product_id = $this->product_id;
        }
        if (array_key_exists($product_id, self::$productsBD)) {
            return self::$productsBD[$product_id];
        } else {
            return Yii::t('bid',"Товар не найден");
        }
    }

    /**
     * @param $all
     * @return array
     */
    public static function getAviableStatus($all = false)
    {
        if ($all) {
            return [
                null => Yii::t('user', "Все"),
                self::STATUS_NEW        => Yii::t('user', static::$statusTitles[self::STATUS_NEW]),
                self::STATUS_ACCEPTED   => Yii::t('user', static::$statusTitles[self::STATUS_ACCEPTED]),
                self::STATUS_REJECTED   => Yii::t('user', static::$statusTitles[self::STATUS_REJECTED]),
                self::STATUS_DEFECT     => Yii::t('user', static::$statusTitles[self::STATUS_DEFECT]),
            ];
        } else {
            return [
                self::STATUS_NEW        => Yii::t('user', static::$statusTitles[self::STATUS_NEW]),
                self::STATUS_ACCEPTED   => Yii::t('user', static::$statusTitles[self::STATUS_ACCEPTED]),
                self::STATUS_REJECTED   => Yii::t('user', static::$statusTitles[self::STATUS_REJECTED]),
                self::STATUS_DEFECT     => Yii::t('user', static::$statusTitles[self::STATUS_DEFECT]),
            ];
        }

    }

    /**
     * @param $status
     * @return string
     */
    public function getStatusTitle($status = null)
    {
        if (!$status) {
            $status = $this->status;
        }
        if (array_key_exists($status, static::$statusTitles)) {
            return self::$statusTitles[$status];
        } else {
            return '';
        }
    }

    public static function getAviableProducts()
    {
        return self::$productsBD;
    }

}
