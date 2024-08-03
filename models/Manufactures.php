<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "manufactures".
 *
 * @property int $id
 * @property string $name
 * @property string|null $website
 * @property int|null $id_region
 * @property int|null $id_city
 * @property string|null $address_loading
 * @property string|null $note
 * @property int|null $create_your_project
 * @property int|null $is_work
 *
 * @property City $city
 * @property Catalog[] $manufactureCatalogs
 * @property ManufactureContacts[] $manufactureContacts
 * @property ManufactureEmails[] $manufactureEmails
 * @property Products[] $manufactureProducts
 * @property City $region
 */
class Manufactures extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'manufactures';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['id_region', 'id_city', 'create_your_project', 'is_work'], 'integer'],
            [['note'], 'string'],
            [['name', 'website', 'address_loading'], 'string', 'max' => 255],
            [['id_city'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['id_city' => 'id']],
            [['id_region'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['id_region' => 'id']],
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
            'website' => 'Website',
            'id_region' => 'Id Region',
            'id_city' => 'Id City',
            'address_loading' => 'Address Loading',
            'note' => 'Note',
            'create_your_project' => 'Create Your Project',
            'is_work' => 'Is Work',
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'id_city']);
    }
    public function getDistrict(){
        return $this->hasOne(City::class, ['id' => $this->getRegion()->one()->parentid]);
    }

    /**
     * Gets query for [[ManufactureCatalogs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManufactureCatalogs()
    {
        return $this->hasMany(Catalog::class, ['id' => 'manufacture_id'])
            ->viaTable('manufacture_catalog', ['manufacture_id' => 'id']);
    }

    /**
     * Gets query for [[ManufactureContacts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManufactureContacts()
    {
        return $this->hasMany(ManufactureContacts::class, ['id_manufacture' => 'id']);
    }

    /**
     * Gets query for [[ManufactureEmails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManufactureEmails()
    {
        return $this->hasMany(ManufactureEmails::class, ['id_manufacture' => 'id']);
    }

    /**
     * Gets query for [[ManufactureProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManufactureProducts()
    {
        return $this->hasMany(Products::class, ['id' => 'product_id'])
            ->viaTable('manufacture_products', ['manufacture_id' => 'id']);
    }

    /**
     * Gets query for [[Region]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(City::class, ['id' => 'id_region']);
    }
}
