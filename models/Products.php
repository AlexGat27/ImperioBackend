<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $name
 * @property int $width
 * @property int $length
 * @property int $height
 * @property int $weight
 *
 * @property Manufactures[] $manufactures
 * @property Category[] $productCategories
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['width', 'length', 'height', 'weight'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'width' => 'Width',
            'length' => 'Length',
            'height' => 'Height',
            'weight' => 'Weight',
        ];
    }

    /**
     * Gets query for [[ManufactureProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManufactureProducts()
    {
        return $this->hasMany(Manufactures::class, ['id' => 'manufacture_id'])
            ->viaTable('manufacture_products', ['product_id' => 'id']);
    }

    /**
     * Gets query for [[ProductCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])
            ->viaTable('product_category', ['product_id' => 'id']);
    }
}
