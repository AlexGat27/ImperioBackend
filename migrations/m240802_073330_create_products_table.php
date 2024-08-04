<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%products}}`.
 */
class m240802_073330_create_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'width' => $this->integer()->notNull()->defaultValue(0),
            'length' => $this->integer()->notNull()->defaultValue(0),
            'height' => $this->integer()->notNull()->defaultValue(0),
            'weight' => $this->integer()->notNull()->defaultValue(0),
        ]);
        $this->createTable('{{%product_category}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'product_id' => $this->integer(),
        ]);
        $this->createTable('{{%manufacture_products}}', [
            'id' => $this->primaryKey(),
            'manufacture_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-manufacture_products-manufacture_id', 'manufacture_products', 'manufacture_id', 'manufactures', 'id', 'CASCADE');
        $this->addForeignKey('fk-manufacture_products-product_id', 'manufacture_products', 'product_id', 'products', 'id', 'CASCADE');
        $this->createIndex('idx-manufacture_products-manufacture_id', 'manufacture_products', 'manufacture_id');
        $this->createIndex('idx-manufacture_products-product_id', 'manufacture_products', 'product_id');

        $this->createIndex('idx-category_id', '{{%product_category}}', 'category_id');
        $this->createIndex('idx-product_id', '{{%product_category}}', 'product_id');
        $this->addForeignKey('fk-product_category-category_id', '{{%product_category}}', 'category_id', '{{%category}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-product_category-product_id', '{{%product_category}}', 'product_id', '{{%products}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-product_category-category_id', '{{%product_category}}');
        $this->dropForeignKey('fk-product_category-product_id', '{{%product_category}}');
        $this->dropIndex('idx-category_id', '{{%product_category}}');
        $this->dropIndex('idx-product_id', '{{%product_category}}');

        $this->dropForeignKey('fk-manufacture_products-manufacture_id', 'manufacture_products');
        $this->dropForeignKey('fk-manufacture_products-product_id', 'manufacture_products');
        $this->dropIndex('idx-manufacture_products-manufacture_id', 'manufacture_products');
        $this->dropIndex('idx-manufacture_products-product_id', 'manufacture_products');

        $this->dropTable('{{%manufacture_products}}');
        $this->dropTable('{{%product_category}}');
        $this->dropTable('{{%products}}');
    }
}
