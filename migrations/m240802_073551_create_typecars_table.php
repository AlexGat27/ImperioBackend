<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%typecars}}`.
 */
class m240802_073551_create_typecars_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%type_cars}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%type_cars}}');
    }
}
