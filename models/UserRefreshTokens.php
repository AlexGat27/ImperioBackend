<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_refresh_tokens".
 *
 * @property int $id
 * @property int $user_id
 * @property string $token Token
 * @property string $ip IP
 * @property string $user_agent User Agent
 * @property string $created_at
 * @property string $expiration_date Token expiration date
 *
 * @property User $user
 */
class UserRefreshTokens extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_refresh_tokens';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'token', 'ip', 'user_agent', 'expiration_date'], 'required'],
            [['user_id'], 'integer'],
            [['created_at', 'expiration_date'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [['token'], 'string', 'max' => 1000],
            [['ip'], 'string', 'max' => 50],
            [['user_agent'], 'string', 'max' => 500],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'token' => 'Token',
            'ip' => 'Ip',
            'user_agent' => 'User Agent',
            'created_at' => 'Created At',
            'expiration_date' => 'Expiration Date',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
