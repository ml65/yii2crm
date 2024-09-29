<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bid".
 *
 * @property int $id
 * @property string $username
 * @property string $title
 * @property string $product_name
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
        0 => 'яблоки',
        1 => 'апельсины',
        2 =>'мандарины'
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
            [['username', 'title', 'product_id', 'price', 'created_at', 'updated_at'], 'required'],
            [['comment'], 'string'],
            [['price'], 'number'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'title', 'product_name'], 'string', 'max' => 255],
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
}
