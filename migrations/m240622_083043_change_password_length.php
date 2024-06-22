<?php

use yii\db\Migration;

/**
 * Class m240622_083043_change_password_length
 */
class m240622_083043_change_password_length extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%users}}', 'password', $this->string(255)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%users}}', 'password', $this->string(50)->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240622_083043_change_password_length cannot be reverted.\n";

        return false;
    }
    */
}
