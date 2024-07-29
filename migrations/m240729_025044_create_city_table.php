<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%city}}`.
 */
class m240729_025044_create_city_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%city}}', [
            'id' => $this->primaryKey(),
            'parentid' => $this->integer(),
            'name' => $this->string(255),
        ]);
        $this->createIndex('idx-city-parentid', '{{%city}}', 'parentid');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-city-parentid', '{{%city}}');
        $this->dropTable('{{%city}}');
    }
}
