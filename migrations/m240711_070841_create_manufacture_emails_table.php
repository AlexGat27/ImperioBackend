<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%manufacture_emails}}`.
 */
class m240711_070841_create_manufacture_emails_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%manufacture_emails}}', [
            'id' => $this->primaryKey(),
            'id_manufacture' => $this->integer()->notNull(),
            'email' => $this->string()->notNull(),
        ]);

        // Индексы и внешние ключи
        $this->createIndex('idx-manufacture_emails-id_manufacture', '{{%manufacture_emails}}', 'id_manufacture');
        $this->addForeignKey('fk-manufacture_emails-id_manufacture', '{{%manufacture_emails}}', 'id_manufacture', '{{%manufactures}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-manufacture_emails-id_manufacture', '{{%manufacture_emails}}');
        $this->dropIndex('idx-manufacture_emails-id_manufacture', '{{%manufacture_emails}}');
        $this->dropTable('{{%manufacture_emails}}');
    }
}
