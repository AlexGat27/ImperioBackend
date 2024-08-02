<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cars_logist}}`.
 */
class m240802_073732_create_carslogist_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cars_logist}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'telephone' => $this->string(50)->notNull(),
            'email' => $this->string(),
            'fedDist_id' => $this->integer(),
            'region_id' => $this->integer(),
            'notes' => $this->string(),
        ]);
        $this->addForeignKey('fk-cars_logist-region_id', 'cars_logist', 'region_id', 'city', 'id');
        $this->addForeignKey('fk-cars_logist-fedDist_id', 'cars_logist', 'fedDist_id', 'city', 'parentid');
        $this->createIndex('idx-cars_logist-region_id', 'cars_logist', 'region_id');
        $this->createIndex('idx-cars_logist-fedDist_id', 'cars_logist', 'fedDist_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-cars_logist-region_id', 'cars_logist');
        $this->dropForeignKey('fk-cars_logist-fedDist_id', 'cars_logist');
        $this->dropIndex('idx-cars_logist-region_id', 'cars_logist');
        $this->dropIndex('idx-cars_logist-fedDist_id', 'cars_logist');
        $this->dropTable('{{%cars_logist}}');
    }
}
