<?php

use yii\db\Migration;

/**
 * Class m230929_175824_user_fields2
 */
class m230929_175824_user_fields2 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('user','image','longblob');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230929_175824_user_fields2 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230929_175824_user_fields2 cannot be reverted.\n";

        return false;
    }
    */
}
