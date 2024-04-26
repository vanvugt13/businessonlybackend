<?php

use yii\db\Migration;

/**
 * Class m240423_201131_addfields_post
 */
class m240423_201131_addfields_post extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('post','subscribe_event','int');
        $this->addColumn('post','subscribe_news','int');
        $this->addColumn('post','event_date','int');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240423_201131_addfields_post cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240423_201131_addfields_post cannot be reverted.\n";

        return false;
    }
    */
}
