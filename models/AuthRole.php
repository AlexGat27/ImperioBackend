<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class AuthRole extends ActiveRecord
{
    public static function tableName()
    {
        return 'auth_item';
    }

    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 64],
            [['name'], 'unique'],
            [['type'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Role Name',
            'type' => 'Role Type',
            'description' => 'Description',
        ];
    }
}
