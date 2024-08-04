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
            'id_district' => $this->integer(),
            'address_loading' => $this->string(),
            'note' => $this->text(),
            'create_your_project' => $this->boolean()->defaultValue(false),
            'is_work' => $this->boolean()->defaultValue(true),
        ]);
        $this->createTable('{{category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
        ]);
        $this->createTable('{{%manufacture_category}}', [
            'id' => $this->primaryKey(),
            'manufacture_id' => $this->integer(),
            'category_id' => $this->integer(),
        ]);

        $this->addForeignKey('fk-manufactures-id_region', 'manufactures', 'id_region', 'city', 'id');
        $this->addForeignKey('fk-manufactures-id_city', 'manufactures', 'id_city', 'city', 'id');
        $this->addForeignKey('fk-manufactures-id_district', 'manufactures', 'id_district', 'city', 'id');
        $this->createIndex('idx-manufactures-id_region', '{{%manufactures}}', 'id_region');
        $this->createIndex('idx-manufactures-id_city', '{{%manufactures}}', 'id_city');
        $this->createIndex('idx-manufactures-id_district', '{{%manufactures}}', 'id_district');

        $this->addForeignKey('fk-manufacture_category-manufacture_id', 'manufacture_category', 'manufacture_id', 'manufactures', 'id', 'CASCADE');
        $this->addForeignKey('fk-manufacture_category-category_id', 'manufacture_category', 'category_id', 'category', 'id', 'CASCADE');
        $this->createIndex('idx-manufacture_category-manufacture_id', 'manufacture_category', 'manufacture_id');
        $this->createIndex('idx-manufacture_category-category_id', 'manufacture_category', 'category_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-manufactures-id_region', 'manufactures');
        $this->dropForeignKey('fk-manufactures-id_city', 'manufactures');
        $this->dropForeignKey('fk-manufactures-id_district', 'manufactures');
        $this->dropIndex('idx-manufactures-id_region', '{{%manufactures}}');
        $this->dropIndex('idx-manufactures-id_city', '{{%manufactures}}');
        $this->dropIndex('idx-manufactures-id_district', '{{%manufactures}}');

        $this->dropForeignKey('fk-manufacture_category-manufacture_id', 'manufacture_category');
        $this->dropForeignKey('fk-manufacture_category-category_id', 'manufacture_category');
        $this->dropIndex('idx-manufacture_category-manufacture_id', 'manufacture_category');
        $this->dropIndex('idx-manufacture_category-category_id', 'manufacture_category');

        $this->dropTable('category');
        $this->dropTable('{{%manufactures}}');
    }
}
