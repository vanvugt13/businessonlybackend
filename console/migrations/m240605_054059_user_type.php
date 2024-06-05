<?php

use yii\db\Migration;

/**
 * Class m240605_054059_user_type
 */
class m240605_054059_user_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user','type','int(2) default 20 COMMENT "20 = app user; 10 = systemuser"');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240605_054059_user_type cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240605_054059_user_type cannot be reverted.\n";

        return false;
    }
    */
}
