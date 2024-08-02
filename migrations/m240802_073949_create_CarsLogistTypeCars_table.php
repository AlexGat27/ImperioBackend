<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%CarsLogistTypeCars}}`.
 */
class m240802_073949_create_CarsLogistTypeCars_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cars_logist_type_cars}}', [
            'id' => $this->primaryKey(),
            'cars_logist_id' => $this->integer(),
            'type_cars_id' => $this->integer(),
        ]);
        $this->addForeignKey('fk-cars_logist_id-cars_logist', 'cars_logist_type_cars', 'cars_logist_id', 'cars_logist', 'id', 'CASCADE');
        $this->addForeignKey('fk-types_cars-types_cars', 'cars_logist_type_cars', 'type_cars_id', 'type_cars', 'id', 'CASCADE');
        $this->createIndex('idx-cars_logist_type_cars-cars_logist_id', 'cars_logist_type_cars', 'cars_logist_id');
        $this->createIndex('idx-cars_logist_type_cars-type_cars_id', 'cars_logist_type_cars', 'type_cars_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-cars_logist_id-cars_logist', 'cars_logist_type_cars');
        $this->dropForeignKey('fk-types_cars-types_cars', 'cars_logist_type_cars');
        $this->dropIndex('idx-cars_logist_type_cars-cars_logist_id', 'cars_logist_type_cars');
        $this->dropIndex('idx-cars_logist_type_cars-type_cars_id', 'cars_logist_type_cars');
        $this->dropTable('{{%cars_logist_type_cars}}');
    }
}
