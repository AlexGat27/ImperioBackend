<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tokens}}`.
 */
class m240619_065107_create_tokens_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_refresh_tokens}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'token' => $this->string(1000)->notNull()->comment('Token'),
            'ip' => $this->string(50)->notNull()->comment('IP'),
            'user_agent' => $this->string(500)->notNull()->comment('User Agent'),
            'created_at' => $this->dateTime()->unsigned()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'expiration_date' => $this->dateTime()->unsigned()->notNull()->comment('Token expiration date'),
        ]);
        $this->addForeignKey(
            'fk-user_refresh_tokens-user_id',
            '{{%user_refresh_tokens}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-user_refresh_tokens-user_id', '{{%user_refresh_tokens}}');
        // Удаление таблицы
        $this->dropTable('{{%user_refresh_tokens}}');
    }
}
