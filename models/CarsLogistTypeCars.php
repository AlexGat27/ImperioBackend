<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cars_logist_type_cars".
 *
 * @property int $id
 * @property int|null $cars_logist_id
 * @property int|null $type_cars_id
 *
 * @property CarsLogist $carsLogist
 * @property TypeCars $typeCars
 */
class CarsLogistTypeCars extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cars_logist_type_cars';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cars_logist_id', 'type_cars_id'], 'integer'],
            [['cars_logist_id'], 'exist', 'skipOnError' => true, 'targetClass' => CarsLogist::class, 'targetAttribute' => ['cars_logist_id' => 'id']],
            [['type_cars_id'], 'exist', 'skipOnError' => true, 'targetClass' => TypeCars::class, 'targetAttribute' => ['type_cars_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cars_logist_id' => 'Cars Logist ID',
            'type_cars_id' => 'Type Cars ID',
        ];
    }

    /**
     * Gets query for [[CarsLogist]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarsLogist()
    {
        return $this->hasOne(CarsLogist::class, ['id' => 'cars_logist_id']);
    }

    /**
     * Gets query for [[TypeCars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTypeCars()
    {
        return $this->hasOne(TypeCars::class, ['id' => 'type_cars_id']);
    }
}
