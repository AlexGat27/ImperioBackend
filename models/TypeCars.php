<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "type_cars".
 *
 * @property int $id
 * @property string $name
 *
 * @property CarsLogistTypeCars[] $carsLogistTypeCars
 */
class TypeCars extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'type_cars';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
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
        ];
    }

    /**
     * Gets query for [[CarsLogistTypeCars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarsLogistTypeCars()
    {
        return $this->hasMany(CarsLogistTypeCars::class, ['type_cars_id' => 'id']);
    }
}
