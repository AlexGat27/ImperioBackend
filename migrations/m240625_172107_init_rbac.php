<?php

use yii\db\Migration;

/**
 * Class m240625_172107_init_rbac
 */
class m240625_172107_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // Создание роли "admin"
        $admin = $auth->createRole('admin');
        $auth->add($admin);

        // Создание роли "user"
        $user = $auth->createRole('user');
        $auth->add($user);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240625_172107_init_rbac cannot be reverted.\n";

        return false;
    }
    */
}
