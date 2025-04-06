<?php

namespace common\models;

use common\interfaces\ProductInterface;
use common\interfaces\StatusInterface;
use common\services\ProductService;
use common\services\StatusService;
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
    private ?ProductService $productService = null;
    private ?StatusService $statusService = null;

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
            [['phone'], 'filter', 'filter' => function($value) {
                $value = preg_replace('/[^0-9]/', '', $value);
                return $value;
            }],
            ['status', 'default', 'value' => StatusService::STATUS_NEW],
            ['status', 'in', 'range' => [
                StatusService::STATUS_NEW,
                StatusService::STATUS_ACCEPTED,
                StatusService::STATUS_REJECTED,
                StatusService::STATUS_DEFECT
            ]],
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

    public function getProductName(int $productId = null): string
    {
        if ($productId === null) {
            $productId = $this->product_id;
        }
        return $this->getProductService()->getProductName($productId);
    }

    public static function getAvailableStatuses(bool $all = false): array
    {
        return (new StatusService())->getAvailableStatuses($all);
    }

    public function getStatusTitle(int $status = null): string
    {
        if ($status === null) {
            $status = $this->status;
        }
        return $this->getStatusService()->getStatusTitle($status);
    }

    public static function getAvailableProducts(): array
    {
        return (new ProductService())->getAvailableProducts();
    }

    private function getProductService(): ProductInterface
    {
        if ($this->productService === null) {
            $this->productService = new ProductService();
        }
        return $this->productService;
    }

    private function getStatusService(): StatusInterface
    {
        if ($this->statusService === null) {
            $this->statusService = new StatusService();
        }
        return $this->statusService;
    }
}
