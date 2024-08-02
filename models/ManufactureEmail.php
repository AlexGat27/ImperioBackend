<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "manufacture_emails".
 *
 * @property int $id
 * @property int $id_manufacture
 * @property string $email
 *
 * @property Manufactures $manufacture
 */
class ManufactureEmail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'manufacture_emails';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_manufacture', 'email'], 'required'],
            [['id_manufacture'], 'integer'],
            [['email'], 'string', 'max' => 255],
            [['id_manufacture'], 'exist', 'skipOnError' => true, 'targetClass' => Manufactures::class, 'targetAttribute' => ['id_manufacture' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_manufacture' => 'Id Manufactures',
            'email' => 'Email',
        ];
    }

    /**
     * Gets query for [[Manufactures]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManufacture()
    {
        return $this->hasOne(Manufactures::class, ['id' => 'id_manufacture']);
    }
}
