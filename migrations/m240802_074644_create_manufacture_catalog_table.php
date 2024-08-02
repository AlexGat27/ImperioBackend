<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%manufacture_catalog}}`.
 */
class m240802_074644_create_manufacture_catalog_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%manufacture_catalog}}', [
            'id' => $this->primaryKey(),
            'manufacture_id' => $this->integer(),
            'catalog_id' => $this->integer(),
        ]);
        $this->addForeignKey('fk-manufacture_catalog-manufacture_id', 'manufacture_catalog', 'manufacture_id', 'manufactures', 'id', 'CASCADE');
        $this->addForeignKey('fk-manufacture_catalog-catalog_id', 'manufacture_catalog', 'catalog_id', 'catalog', 'id', 'CASCADE');
        $this->createIndex('idx-manufacture_catalog-manufacture_id', 'manufacture_catalog', 'manufacture_id');
        $this->createIndex('idx-manufacture_catalog-catalog_id', 'manufacture_catalog', 'catalog_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-manufacture_catalog-manufacture_id', 'manufacture_catalog');
        $this->dropForeignKey('fk-manufacture_catalog-catalog_id', 'manufacture_catalog');
        $this->dropIndex('idx-manufacture_catalog-manufacture_id', 'manufacture_catalog');
        $this->dropIndex('idx-manufacture_catalog-catalog_id', 'manufacture_catalog');
        $this->dropTable('{{%manufacture_catalog}}');
    }
}
