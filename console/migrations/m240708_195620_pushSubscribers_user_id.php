<?php

use yii\db\Migration;

/**
 * Class m240708_195620_pushSubscribers_user_id
 */
class m240708_195620_pushSubscribers_user_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('push_subscribers','user_id','int');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240708_195620_pushSubscribers_user_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240708_195620_pushSubscribers_user_id cannot be reverted.\n";

        return false;
    }
    */
}
