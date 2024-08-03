<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cars_logist".
 *
 * @property int $id
 * @property string $name
 * @property string $telephone
 * @property string|null $email
 * @property int|null $fedDist_id
 * @property int|null $region_id
 * @property string|null $notes
 *
 * @property TypeCars[] $carsLogistTypeCars
 * @property City $fedDist
 * @property City $region
 */
class CarsLogist extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cars_logist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'telephone'], 'required'],
            [['fedDist_id', 'region_id'], 'integer'],
            [['name', 'email', 'notes'], 'string', 'max' => 255],
            [['telephone'], 'string', 'max' => 50],
            [['fedDist_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['fedDist_id' => 'parentid']],
            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['region_id' => 'id']],
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
            'telephone' => 'Telephone',
            'email' => 'Email',
            'fedDist_id' => 'Fed Dist ID',
            'region_id' => 'Region ID',
            'notes' => 'Notes',
        ];
    }

    /**
     * Gets query for [[CarsLogistTypeCars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTypeCars()
    {
        return $this->hasMany(TypeCars::class, ['id' => 'cars_logist_id'])
            ->viaTable('type_cars', ['type_cars_id' => 'id']);
    }

    /**
     * Gets query for [[FedDist]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(City::class, ['id' => 'fedDist_id']);
    }

    /**
     * Gets query for [[Region]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(City::class, ['id' => 'region_id']);
    }
}
