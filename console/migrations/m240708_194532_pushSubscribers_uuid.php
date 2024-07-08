<?php

use yii\db\Migration;

/**
 * Class m240708_194532_pushSubscribers_uuid
 */
class m240708_194532_pushSubscribers_uuid extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('push_subscribers','uuid','VARCHAR(255)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240708_194532_pushSubscribers_uuid cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240708_194532_pushSubscribers_uuid cannot be reverted.\n";

        return false;
    }
    */
}
