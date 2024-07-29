<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m240617_093606_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'login' => $this->string(50)->notNull()->unique(),
            'password' => $this->string(255)->notNull(),
            'name' => $this->string(50)->notNull(),
            'surname' => $this->string(50)->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
        $this->createIndex(
            'idx-unique-login',
            '{{%users}}',
            'login',
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-unique-login', '{{%users}}');
        $this->dropTable('{{%users}}');
    }
}
