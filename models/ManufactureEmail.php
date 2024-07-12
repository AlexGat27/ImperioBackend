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
 * @property Manufacture $manufacture
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
            [['id_manufacture'], 'exist', 'skipOnError' => true, 'targetClass' => Manufacture::class, 'targetAttribute' => ['id_manufacture' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_manufacture' => 'Id Manufacture',
            'email' => 'Email',
        ];
    }

    /**
     * Gets query for [[Manufacture]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManufacture()
    {
        return $this->hasOne(Manufacture::class, ['id' => 'id_manufacture']);
    }
}
