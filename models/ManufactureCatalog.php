<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "manufacture_catalog".
 *
 * @property int $id
 * @property int|null $manufacture_id
 * @property int|null $catalog_id
 *
 * @property Catalog $catalog
 * @property Manufactures $manufacture
 */
class ManufactureCatalog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'manufacture_catalog';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['manufacture_id', 'catalog_id'], 'integer'],
            [['catalog_id'], 'exist', 'skipOnError' => true, 'targetClass' => Catalog::class, 'targetAttribute' => ['catalog_id' => 'id']],
            [['manufacture_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manufactures::class, 'targetAttribute' => ['manufacture_id' => 'id']],
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
            'catalog_id' => 'Catalog ID',
        ];
    }

    /**
     * Gets query for [[Catalog]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCatalog()
    {
        return $this->hasOne(Catalog::class, ['id' => 'catalog_id']);
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
}
