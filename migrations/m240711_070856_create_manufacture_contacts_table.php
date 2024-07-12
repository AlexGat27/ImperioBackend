<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%manufacture_contacts}}`.
 */
class m240711_070856_create_manufacture_contacts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%manufacture_contacts}}', [
            'id' => $this->primaryKey(),
            'id_manufacture' => $this->integer()->notNull(),
            'telephone' => $this->string()->notNull(),
            'name_personal' => $this->string()->notNull(),
            'note' => $this->text(),
        ]);

        // Индексы и внешние ключи
        $this->createIndex('idx-manufacture_contacts-id_manufacture', '{{%manufacture_contacts}}', 'id_manufacture');
        $this->addForeignKey('fk-manufacture_contacts-id_manufacture', '{{%manufacture_contacts}}', 'id_manufacture', '{{%manufactures}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-manufacture_contacts-id_manufacture', '{{%manufacture_contacts}}');
        $this->dropIndex('idx-manufacture_contacts-id_manufacture', '{{%manufacture_contacts}}');
        $this->dropTable('{{%manufacture_contacts}}');
    }
}
