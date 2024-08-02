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
 * @property boolean $create_your_project
 * @property boolean $is_work
 *
 * @property ManufactureContact[] $manufactureContacts
 * @property ManufactureEmail[] $manufactureEmails
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
            [['name', 'is_work', 'create_your_project'], 'required'],
            [['id_region', 'id_city'], 'integer'],
            [['is_work', 'create_your_project'], 'boolean'],
            [['note'], 'string'],
            [['name', 'website', 'address_loading'], 'string', 'max' => 255],
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
     * Gets query for [[ManufactureContact]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManufactureContacts()
    {
        return $this->hasMany(ManufactureContact::class, ['id_manufacture' => 'id']);
    }

    /**
     * Gets query for [[ManufactureEmail]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManufactureEmails()
    {
        return $this->hasMany(ManufactureEmail::class, ['id_manufacture' => 'id']);
    }
}
