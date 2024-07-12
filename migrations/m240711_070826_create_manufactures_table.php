<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%manufactures}}`.
 */
class m240711_070826_create_manufactures_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%manufactures}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'website' => $this->string(),
            'id_region' => $this->integer(),
            'id_city' => $this->integer(),
            'address_loading' => $this->string(),
            'note' => $this->text(),
            'create_your_project' => $this->boolean()->defaultValue(false),
            'is_work' => $this->boolean()->defaultValue(true),
        ]);

        // Индексы
        $this->createIndex('idx-manufactures-id_region', '{{%manufactures}}', 'id_region');
        $this->createIndex('idx-manufactures-id_city', '{{%manufactures}}', 'id_city');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-manufactures-id_region', '{{%manufactures}}');
        $this->dropIndex('idx-manufactures-id_city', '{{%manufactures}}');
        $this->dropTable('{{%manufactures}}');
    }
}
