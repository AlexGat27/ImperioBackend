<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%manufacture_products}}`.
 */
class m240802_074618_create_manufacture_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%manufacture_products}}', [
            'id' => $this->primaryKey(),
            'manufacture_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk-manufacture_products-manufacture_id', 'manufacture_products', 'manufacture_id', 'manufactures', 'id', 'CASCADE');
        $this->addForeignKey('fk-manufacture_products-product_id', 'manufacture_products', 'product_id', 'products', 'id', 'CASCADE');
        $this->createIndex('idx-manufacture_products-manufacture_id', 'manufacture_products', 'manufacture_id');
        $this->createIndex('idx-manufacture_products-product_id', 'manufacture_products', 'product_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-manufacture_products-manufacture_id', 'manufacture_products');
        $this->dropForeignKey('fk-manufacture_products-product_id', 'manufacture_products');
        $this->dropIndex('idx-manufacture_products-manufacture_id', 'manufacture_products');
        $this->dropIndex('idx-manufacture_products-product_id', 'manufacture_products');
        $this->dropTable('{{%manufacture_products}}');
    }
}
