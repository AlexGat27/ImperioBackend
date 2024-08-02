<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "manufacture_products".
 *
 * @property int $id
 * @property int $manufacture_id
 * @property int $product_id
 *
 * @property Manufactures $manufacture
 * @property Products $product
 */
class ManufactureProducts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'manufacture_products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['manufacture_id', 'product_id'], 'required'],
            [['manufacture_id', 'product_id'], 'integer'],
            [['manufacture_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manufactures::class, 'targetAttribute' => ['manufacture_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'manufacture_id' => 'Manufactures ID',
            'product_id' => 'Product ID',
        ];
    }

    /**
     * Gets query for [[Manufactures]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManufacture()
    {
        return $this->hasOne(Manufactures::class, ['id' => 'manufacture_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::class, ['id' => 'product_id']);
    }
}
